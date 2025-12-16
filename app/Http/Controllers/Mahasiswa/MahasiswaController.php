<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MahasiswaRepositoryInterface;
use App\Repositories\Contracts\JadwalRepositoryInterface;
use App\Repositories\Contracts\RencanaStudiRepositoryInterface;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    protected MahasiswaRepositoryInterface $mahasiswaRepository;
    protected JadwalRepositoryInterface $jadwalRepository;
    protected RencanaStudiRepositoryInterface $rencanaStudiRepository;

    public function __construct(
        MahasiswaRepositoryInterface $mahasiswaRepository,
        JadwalRepositoryInterface $jadwalRepository,
        RencanaStudiRepositoryInterface $rencanaStudiRepository
    ) {
        $this->mahasiswaRepository = $mahasiswaRepository;
        $this->jadwalRepository = $jadwalRepository;
        $this->rencanaStudiRepository = $rencanaStudiRepository;
    }

    public function dashboard()
    {
        $nim = auth()->user()->kode;
        $mahasiswa = $this->mahasiswaRepository->findByNim($nim);
        
        $statistics = [
            'total_sks' => $this->mahasiswaRepository->getTotalSks($nim),
            'ipk' => $this->mahasiswaRepository->calculateIpk($nim),
            'semester_aktif' => $this->getSemesterAktif($nim),
            'mata_kuliah_aktif' => $this->rencanaStudiRepository->getByMahasiswa($nim)->count(),
        ];

        $jadwalHariIni = $this->getJadwalHariIni($nim);

        return view('mahasiswa.dashboard', compact('mahasiswa', 'statistics', 'jadwalHariIni'));
    }

    public function krs()
    {
        $nim = auth()->user()->kode;
        $mahasiswa = $this->mahasiswaRepository->findByNim($nim);
        $krs = $this->rencanaStudiRepository->getByMahasiswaWithDetails($nim);
        $totalSks = $this->rencanaStudiRepository->getTotalSksKrs($nim);
        $availableJadwal = $this->jadwalRepository->getWithRelations();
        
        // Check if any KRS is submitted
        $isSubmitted = $krs->where('status', 'submitted')->isNotEmpty();

        return view('mahasiswa.krs', compact('mahasiswa', 'krs', 'totalSks', 'availableJadwal', 'isSubmitted'));
    }

    public function addKrs(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
        ]);

        $nim = auth()->user()->kode;
        $jadwal = $this->jadwalRepository->findById($request->jadwal_id);

        if (!$jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan');
        }

        // Check max 24 SKS
        $currentSks = $this->rencanaStudiRepository->getTotalSksKrs($nim);
        $newSks = $jadwal->mataKuliah->sks ?? 0;
        
        if (($currentSks + $newSks) > 24) {
            return back()->with('error', 'Total SKS melebihi batas maksimal 24 SKS');
        }

        // Check duplicate mata kuliah
        if ($this->rencanaStudiRepository->checkDuplicateMataKuliah($nim, $jadwal->mata_kuliah_id)) {
            return back()->with('error', 'Mata kuliah sudah diambil');
        }

        // Check schedule conflict
        if ($this->rencanaStudiRepository->checkScheduleConflict($nim, $jadwal->hari, $jadwal->jam)) {
            return back()->with('error', 'Jadwal bentrok dengan mata kuliah lain');
        }

        $this->rencanaStudiRepository->addToKrs($nim, $request->jadwal_id);

        return back()->with('success', 'Mata kuliah berhasil ditambahkan ke KRS');
    }

    public function removeKrs(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
        ]);

        $nim = auth()->user()->kode;
        
        $removed = $this->rencanaStudiRepository->removeFromKrs($nim, $request->jadwal_id);

        if (!$removed) {
            return back()->with('error', 'Gagal menghapus mata kuliah dari KRS. Mungkin KRS sudah disubmit.');
        }

        return back()->with('success', 'Mata kuliah berhasil dihapus dari KRS');
    }

    public function submitKrs()
    {
        $nim = auth()->user()->kode;
        
        $krs = $this->rencanaStudiRepository->getByMahasiswa($nim);
        
        if ($krs->isEmpty()) {
            return back()->with('error', 'KRS kosong. Tambahkan mata kuliah terlebih dahulu.');
        }

        $this->rencanaStudiRepository->submitKrs($nim);

        return back()->with('success', 'KRS berhasil disubmit');
    }

    public function printKrs()
    {
        $nim = auth()->user()->kode;
        $mahasiswa = $this->mahasiswaRepository->findByNim($nim);
        $krs = $this->rencanaStudiRepository->getByMahasiswaWithDetails($nim);
        $totalSks = $this->rencanaStudiRepository->getTotalSksKrs($nim);

        return view('mahasiswa.krs_print', compact('mahasiswa', 'krs', 'totalSks'));
    }

    public function jadwal()
    {
        $nim = auth()->user()->kode;
        $krs = $this->rencanaStudiRepository->getByMahasiswaWithDetails($nim);

        // Group by day
        $jadwalByHari = $krs->groupBy(fn($item) => $item->jadwal->hari ?? 'Tidak Terjadwal');

        return view('mahasiswa.jadwal', compact('jadwalByHari'));
    }

    public function nilai()
    {
        $nim = auth()->user()->kode;
        $krs = $this->rencanaStudiRepository->getByMahasiswaWithDetails($nim)
            ->filter(fn($item) => $item->nilai_huruf !== null);
        $ipk = $this->mahasiswaRepository->calculateIpk($nim);
        $totalSks = $this->mahasiswaRepository->getTotalSks($nim);

        return view('mahasiswa.nilai', compact('krs', 'ipk', 'totalSks'));
    }

    public function hasilStudi()
    {
        $nim = auth()->user()->kode;
        $mahasiswa = $this->mahasiswaRepository->findByNim($nim);
        $krs = $this->rencanaStudiRepository->getByMahasiswaWithDetails($nim);
        
        $statistics = [
            'total_sks' => $this->mahasiswaRepository->getTotalSks($nim),
            'sks_lulus' => $this->getSksLulus($nim),
            'total_mata_kuliah' => $krs->count(),
            'ipk' => $this->mahasiswaRepository->calculateIpk($nim),
        ];

        return view('mahasiswa.hasil_studi', compact('mahasiswa', 'krs', 'statistics'));
    }

    private function getJadwalHariIni(string $nim)
    {
        $hariIndonesia = $this->getHariIndonesia();
        
        return $this->rencanaStudiRepository->getByMahasiswaWithDetails($nim)
            ->filter(fn($item) => $item->jadwal && $item->jadwal->hari === $hariIndonesia)
            ->sortBy(fn($item) => $item->jadwal->jam ?? '');
    }

    private function getSemesterAktif(string $nim): int
    {
        // Simple calculation based on NIM year
        $tahunMasuk = (int) substr($nim, 0, 4);
        $tahunSekarang = (int) date('Y');
        $bulanSekarang = (int) date('n');
        
        $tahunBerjalan = $tahunSekarang - $tahunMasuk;
        $semester = ($tahunBerjalan * 2) + ($bulanSekarang >= 7 ? 1 : 0);
        
        return max(1, $semester);
    }

    private function getSksLulus(string $nim): int
    {
        $krs = $this->rencanaStudiRepository->getByMahasiswaWithDetails($nim);
        
        return $krs->filter(function ($item) {
            // Passing grade is C or above (mutu >= 2.00)
            return $item->nilai_huruf && in_array($item->nilai_huruf, ['A', 'A-', 'B', 'B-', 'C']);
        })->sum(fn($item) => $item->jadwal?->mataKuliah?->sks ?? 0);
    }

    private function getHariIndonesia(): string
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        
        return $days[date('l')] ?? 'Senin';
    }

    // EPBM Methods
    public function epbmIndex()
    {
        $nim = auth()->user()->kode;
        
        // Get all rencana_studi with nilai that haven't been fully evaluated
        $krs = $this->rencanaStudiRepository->getByMahasiswaWithDetails($nim)
            ->filter(function ($item) {
                // Only show courses that have nilai and are submitted
                return $item->nilai_huruf !== null && $item->status === 'submitted';
            });

        // Check which ones have been answered
        $krsWithStatus = $krs->map(function ($item) {
            $jadwal = $item->jadwal;
            $dosenCount = $jadwal?->dosen?->count() ?? 0;
            
            // Count answered questions for this rencana_studi
            $answeredCount = \App\Models\EpbmJawaban::where('rencana_studi_id', $item->id)->count();
            
            // Get active periode questions count
            $periode = \App\Models\EpbmPeriode::whereHas('jurusan.prodi', function($q) {
                // Get from current context - simplified
            })->where('is_active', true)->first();
            
            $totalQuestions = $periode ? $periode->pertanyaan->count() * $dosenCount : 0;
            
            $item->epbm_status = $answeredCount >= $totalQuestions ? 'completed' : 'pending';
            $item->dosen_count = $dosenCount;
            
            return $item;
        });

        return view('mahasiswa.epbm.index', compact('krsWithStatus'));
    }

    public function epbmForm(int $rencanaStudiId)
    {
        $nim = auth()->user()->kode;
        $rencanaStudi = \App\Models\RencanaStudi::with(['jadwal.dosen', 'jadwal.mataKuliah'])
            ->where('nim', $nim)
            ->findOrFail($rencanaStudiId);

        // Check if nilai exists
        if (!$rencanaStudi->nilai_huruf) {
            return back()->with('error', 'EPBM hanya tersedia setelah nilai dirilis');
        }

        // Get active EPBM periode
        $periode = \App\Models\EpbmPeriode::where('is_active', true)->first();
        
        if (!$periode) {
            return back()->with('error', 'Tidak ada periode EPBM aktif');
        }

        $pertanyaan = $periode->pertanyaan;
        $dosenList = $rencanaStudi->jadwal->dosen;

        // Get existing answers
        $existingAnswers = \App\Models\EpbmJawaban::where('rencana_studi_id', $rencanaStudiId)
            ->whereIn('epbm_pertanyaan_id', $pertanyaan->pluck('id'))
            ->get()
            ->keyBy(fn($j) => $j->epbm_pertanyaan_id . '-' . $j->nidn);

        return view('mahasiswa.epbm.form', compact('rencanaStudi', 'periode', 'pertanyaan', 'dosenList', 'existingAnswers'));
    }

    public function epbmSubmit(Request $request, int $rencanaStudiId)
    {
        $nim = auth()->user()->kode;
        $rencanaStudi = \App\Models\RencanaStudi::where('nim', $nim)->findOrFail($rencanaStudiId);

        $request->validate([
            'jawaban' => 'required|array',
        ]);

        foreach ($request->jawaban as $pertanyaanId => $dosenAnswers) {
            foreach ($dosenAnswers as $nidn => $value) {
                $pertanyaan = \App\Models\EpbmPertanyaan::find($pertanyaanId);
                
                $data = [
                    'epbm_pertanyaan_id' => $pertanyaanId,
                    'rencana_studi_id' => $rencanaStudiId,
                    'nidn' => $nidn,
                ];

                if ($pertanyaan->isRating()) {
                    $data['nilai_rating'] = (int) $value;
                } else {
                    $data['jawaban_text'] = $value;
                }

                \App\Models\EpbmJawaban::updateOrCreate(
                    [
                        'epbm_pertanyaan_id' => $pertanyaanId,
                        'rencana_studi_id' => $rencanaStudiId,
                        'nidn' => $nidn,
                    ],
                    $data
                );
            }
        }

        return redirect()->route('mahasiswa.epbm.index')->with('success', 'Jawaban EPBM berhasil disimpan');
    }

    // Absensi Method
    public function absensiIndex()
    {
        $nim = auth()->user()->kode;
        $krs = $this->rencanaStudiRepository->getByMahasiswaWithDetails($nim)
            ->filter(fn($item) => $item->status === 'submitted');

        // Get absensi for each
        $krsWithAbsensi = $krs->map(function ($item) {
            $absensi = \App\Models\Absensi::where('rencana_studi_id', $item->id)
                ->orderBy('pertemuan_ke')
                ->get();
            
            $hadirCount = $absensi->where('status', 'hadir')->count();
            $totalPertemuan = $absensi->count();
            
            $item->absensi = $absensi;
            $item->hadir_count = $hadirCount;
            $item->total_pertemuan = $totalPertemuan;
            $item->persentase_kehadiran = $totalPertemuan > 0 
                ? round(($hadirCount / $totalPertemuan) * 100, 1) 
                : 0;
            
            return $item;
        });

        return view('mahasiswa.absensi.index', compact('krsWithAbsensi'));
    }
}
