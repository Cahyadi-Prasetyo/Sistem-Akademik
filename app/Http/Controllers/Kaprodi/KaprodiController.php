<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\EpbmPeriode;
use App\Models\EpbmPertanyaan;
use App\Models\EpbmJawaban;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class KaprodiController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $prodi = $user->prodiKaprodi;
        
        $statistics = [];
        
        if ($prodi) {
            $jurusan = $prodi->jurusan;
            $periodeAktif = EpbmPeriode::where('jurusan_id', $jurusan->id)
                ->where('is_active', true)
                ->first();
            
            $totalPeriode = EpbmPeriode::where('jurusan_id', $jurusan->id)->count();
            $totalPertanyaan = $periodeAktif ? $periodeAktif->pertanyaan->count() : 0;
            $totalJawaban = $periodeAktif ? EpbmJawaban::whereHas('pertanyaan', function($q) use ($periodeAktif) {
                $q->where('epbm_periode_id', $periodeAktif->id);
            })->count() : 0;
            
            $statistics = [
                'total_periode' => $totalPeriode,
                'periode_aktif' => $periodeAktif?->nama_periode ?? 'Tidak ada',
                'total_pertanyaan' => $totalPertanyaan,
                'total_jawaban' => $totalJawaban,
            ];
        }
        
        return view('kaprodi.dashboard', compact('user', 'prodi', 'statistics'));
    }

    // EPBM Periode CRUD
    public function epbmIndex()
    {
        $prodi = auth()->user()->prodiKaprodi;
        $jurusan = $prodi?->jurusan;
        
        $periodeList = $jurusan 
            ? EpbmPeriode::where('jurusan_id', $jurusan->id)->orderBy('created_at', 'desc')->get()
            : collect();
        
        return view('kaprodi.epbm.index', compact('periodeList'));
    }

    public function epbmCreate()
    {
        return view('kaprodi.epbm.create');
    }

    public function epbmStore(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $prodi = auth()->user()->prodiKaprodi;
        
        if (!$prodi || !$prodi->jurusan) {
            return back()->with('error', 'Prodi tidak ditemukan');
        }

        EpbmPeriode::create([
            'jurusan_id' => $prodi->jurusan->id,
            'nama_periode' => $request->nama_periode,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => false,
        ]);

        return redirect()->route('kaprodi.epbm.index')->with('success', 'Periode EPBM berhasil dibuat');
    }

    public function epbmShow(int $id)
    {
        $periode = EpbmPeriode::with('pertanyaan')->findOrFail($id);
        return view('kaprodi.epbm.show', compact('periode'));
    }

    public function epbmEdit(int $id)
    {
        $periode = EpbmPeriode::findOrFail($id);
        return view('kaprodi.epbm.edit', compact('periode'));
    }

    public function epbmUpdate(Request $request, int $id)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $periode = EpbmPeriode::findOrFail($id);
        $periode->update($request->only(['nama_periode', 'tanggal_mulai', 'tanggal_selesai']));

        return redirect()->route('kaprodi.epbm.index')->with('success', 'Periode EPBM berhasil diperbarui');
    }

    public function epbmDestroy(int $id)
    {
        EpbmPeriode::findOrFail($id)->delete();
        return redirect()->route('kaprodi.epbm.index')->with('success', 'Periode EPBM berhasil dihapus');
    }

    public function epbmToggleActive(int $id)
    {
        $periode = EpbmPeriode::findOrFail($id);
        
        // Deactivate all other periods in same jurusan
        EpbmPeriode::where('jurusan_id', $periode->jurusan_id)
            ->where('id', '!=', $id)
            ->update(['is_active' => false]);
        
        $periode->update(['is_active' => !$periode->is_active]);

        return back()->with('success', 'Status periode berhasil diubah');
    }

    // Pertanyaan CRUD
    public function pertanyaanStore(Request $request, int $id)
    {
        $request->validate([
            'pertanyaan' => 'required|string',
            'jenis' => 'required|in:rating,text',
        ]);

        $periode = EpbmPeriode::findOrFail($id);
        $maxUrutan = $periode->pertanyaan()->max('urutan') ?? 0;

        EpbmPertanyaan::create([
            'epbm_periode_id' => $id,
            'urutan' => $maxUrutan + 1,
            'pertanyaan' => $request->pertanyaan,
            'jenis' => $request->jenis,
        ]);

        return back()->with('success', 'Pertanyaan berhasil ditambahkan');
    }

    public function pertanyaanUpdate(Request $request, int $id)
    {
        $request->validate([
            'pertanyaan' => 'required|string',
            'jenis' => 'required|in:rating,text',
        ]);

        EpbmPertanyaan::findOrFail($id)->update($request->only(['pertanyaan', 'jenis']));

        return back()->with('success', 'Pertanyaan berhasil diperbarui');
    }

    public function pertanyaanDestroy(int $id)
    {
        EpbmPertanyaan::findOrFail($id)->delete();
        return back()->with('success', 'Pertanyaan berhasil dihapus');
    }

    // Summary & Jawaban
    public function summary()
    {
        $prodi = auth()->user()->prodiKaprodi;
        $jurusan = $prodi?->jurusan;
        
        $periodeList = $jurusan
            ? EpbmPeriode::where('jurusan_id', $jurusan->id)
                ->withCount('pertanyaan')
                ->orderBy('created_at', 'desc')
                ->get()
            : collect();

        return view('kaprodi.summary.index', compact('periodeList'));
    }

    public function jawabanIndex(int $periodeId)
    {
        $periode = EpbmPeriode::with(['pertanyaan.jawaban.rencanaStudi.mahasiswa'])->findOrFail($periodeId);
        
        // Get unique mahasiswa who answered
        $jawabanGrouped = EpbmJawaban::whereHas('pertanyaan', function($q) use ($periodeId) {
            $q->where('epbm_periode_id', $periodeId);
        })
        ->with('rencanaStudi.mahasiswa')
        ->get()
        ->groupBy(fn($j) => $j->rencanaStudi?->nim);

        return view('kaprodi.summary.jawaban', compact('periode', 'jawabanGrouped'));
    }

    public function jawabanDetail(int $periodeId, string $nim)
    {
        $periode = EpbmPeriode::with('pertanyaan')->findOrFail($periodeId);
        
        $jawaban = EpbmJawaban::whereHas('pertanyaan', function($q) use ($periodeId) {
            $q->where('epbm_periode_id', $periodeId);
        })
        ->whereHas('rencanaStudi', function($q) use ($nim) {
            $q->where('nim', $nim);
        })
        ->with(['pertanyaan', 'dosen'])
        ->get();

        return view('kaprodi.summary.detail', compact('periode', 'nim', 'jawaban'));
    }
}
