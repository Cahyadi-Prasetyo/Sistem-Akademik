<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\NilaiMutu;
use App\Repositories\Contracts\DosenRepositoryInterface;
use App\Repositories\Contracts\JadwalRepositoryInterface;
use App\Repositories\Contracts\RencanaStudiRepositoryInterface;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    protected DosenRepositoryInterface $dosenRepository;
    protected JadwalRepositoryInterface $jadwalRepository;
    protected RencanaStudiRepositoryInterface $rencanaStudiRepository;

    public function __construct(
        DosenRepositoryInterface $dosenRepository,
        JadwalRepositoryInterface $jadwalRepository,
        RencanaStudiRepositoryInterface $rencanaStudiRepository
    ) {
        $this->dosenRepository = $dosenRepository;
        $this->jadwalRepository = $jadwalRepository;
        $this->rencanaStudiRepository = $rencanaStudiRepository;
    }

    public function dashboard()
    {
        $nidn = auth()->user()->kode;
        $dosen = $this->dosenRepository->getWithJadwal($nidn);
        
        $statistics = [
            'total_jadwal' => $dosen?->jadwal?->count() ?? 0,
            'total_mahasiswa' => $this->dosenRepository->getTotalMahasiswa($nidn),
            'total_mata_kuliah' => $dosen?->jadwal?->pluck('mata_kuliah_id')->unique()->count() ?? 0,
            'nilai_pending' => $this->countPendingGrades($nidn),
        ];

        $jadwalHariIni = $this->dosenRepository->getJadwalHariIni($nidn);

        return view('dosen.dashboard', compact('dosen', 'statistics', 'jadwalHariIni'));
    }

    public function jadwal()
    {
        $nidn = auth()->user()->kode;
        $jadwal = $this->jadwalRepository->getByDosen($nidn);

        return view('dosen.jadwal', compact('jadwal'));
    }

    public function nilai()
    {
        $nidn = auth()->user()->kode;
        $jadwal = $this->jadwalRepository->getByDosen($nidn);

        return view('dosen.nilai', compact('jadwal'));
    }

    public function getMahasiswaByJadwal(int $jadwalId)
    {
        $rencanaStudi = $this->rencanaStudiRepository->getByJadwal($jadwalId);

        return response()->json([
            'data' => $rencanaStudi->map(function ($krs) {
                return [
                    'id' => $krs->id,
                    'nim' => $krs->nim,
                    'nama' => $krs->mahasiswa->nama ?? '-',
                    'nilai_angka' => $krs->nilai_angka,
                    'nilai_huruf' => $krs->nilai_huruf,
                ];
            }),
        ]);
    }

    public function saveNilai(Request $request)
    {
        $request->validate([
            'nilai' => 'required|array',
            'nilai.*.id' => 'required|exists:rencana_studi,id',
            'nilai.*.nilai_angka' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($request->nilai as $item) {
            if (isset($item['nilai_angka']) && $item['nilai_angka'] !== null) {
                $nilaiHuruf = NilaiMutu::getLetterGrade((float) $item['nilai_angka']);
                $this->rencanaStudiRepository->updateNilai(
                    $item['id'],
                    (float) $item['nilai_angka'],
                    $nilaiHuruf
                );
            }
        }

        return redirect()->route('dosen.nilai')
            ->with('success', 'Nilai berhasil disimpan');
    }

    private function countPendingGrades(string $nidn): int
    {
        $jadwal = $this->jadwalRepository->getByDosen($nidn);
        $pendingCount = 0;

        foreach ($jadwal as $jdw) {
            $pendingCount += $jdw->rencanaStudi()
                ->whereNull('nilai_angka')
                ->where('status', 'submitted')
                ->count();
        }

        return $pendingCount;
    }

    // Absensi Methods
    public function absensiIndex()
    {
        $nidn = auth()->user()->kode;
        $jadwal = $this->jadwalRepository->getByDosen($nidn);

        return view('dosen.absensi.index', compact('jadwal'));
    }

    public function absensiJadwal(int $jadwalId)
    {
        $nidn = auth()->user()->kode;
        
        // Verify ownership
        $jadwal = $this->jadwalRepository->findById($jadwalId);
        if (!$jadwal->dosen->contains('nidn', $nidn)) {
            return back()->with('error', 'Anda tidak memiliki akses ke jadwal ini');
        }

        // Get mahasiswa in this jadwal
        $mahasiswaList = \App\Models\RencanaStudi::with('mahasiswa')
            ->where('jadwal_id', $jadwalId)
            ->where('status', 'submitted')
            ->get();

        // Get existing attendance for today or specific date
        $pertemuanKe = request('pertemuan_ke', 1);
        
        $absensi = \App\Models\Absensi::whereIn('rencana_studi_id', $mahasiswaList->pluck('id'))
            ->where('pertemuan_ke', $pertemuanKe)
            ->get()
            ->keyBy('rencana_studi_id');

        return view('dosen.absensi.jadwal', compact('jadwal', 'mahasiswaList', 'pertemuanKe', 'absensi'));
    }

    public function absensiSave(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'pertemuan_ke' => 'required|integer|min:1|max:16',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*.rencana_studi_id' => 'required|exists:rencana_studi,id',
            'absensi.*.status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        foreach ($request->absensi as $item) {
            \App\Models\Absensi::updateOrCreate(
                [
                    'rencana_studi_id' => $item['rencana_studi_id'],
                    'pertemuan_ke' => $request->pertemuan_ke,
                ],
                [
                    'tanggal' => $request->tanggal,
                    'status' => $item['status'],
                    'keterangan' => $item['keterangan'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Data absensi berhasil disimpan');
    }
}
