<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\EpbmPeriode;
use App\Models\EpbmPertanyaan;
use App\Models\EpbmJawaban;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class PimpinanController extends Controller
{
    public function dashboard()
    {
        $jurusanList = Jurusan::withCount(['epbmPeriode'])->get();
        
        $statistics = [
            'total_jurusan' => $jurusanList->count(),
            'total_periode' => EpbmPeriode::count(),
            'total_pertanyaan' => EpbmPertanyaan::count(),
            'total_jawaban' => EpbmJawaban::count(),
        ];

        return view('pimpinan.dashboard', compact('jurusanList', 'statistics'));
    }

    public function summary()
    {
        $jurusanList = Jurusan::with(['fakultas', 'epbmPeriode' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->get();

        return view('pimpinan.summary.index', compact('jurusanList'));
    }

    public function summaryPeriode(int $id)
    {
        $periode = EpbmPeriode::with(['jurusan', 'pertanyaan'])->findOrFail($id);
        
        // Calculate summary per pertanyaan
        $summaryData = $periode->pertanyaan->map(function($pertanyaan) {
            $jawaban = $pertanyaan->jawaban;
            
            if ($pertanyaan->isRating()) {
                $avgRating = $jawaban->avg('nilai_rating');
                $totalResponses = $jawaban->whereNotNull('nilai_rating')->count();
                
                return [
                    'pertanyaan' => $pertanyaan,
                    'avg_rating' => round($avgRating, 2),
                    'total_responses' => $totalResponses,
                    'distribution' => $jawaban->groupBy('nilai_rating')->map->count(),
                ];
            } else {
                return [
                    'pertanyaan' => $pertanyaan,
                    'total_responses' => $jawaban->whereNotNull('jawaban_text')->count(),
                    'answers' => $jawaban->pluck('jawaban_text')->filter(),
                ];
            }
        });

        return view('pimpinan.summary.periode', compact('periode', 'summaryData'));
    }

    public function summaryPertanyaan(int $periodeId)
    {
        $periode = EpbmPeriode::with(['pertanyaan.jawaban'])->findOrFail($periodeId);
        
        // Get per-question summary with dosen breakdown
        $pertanyaanSummary = $periode->pertanyaan->map(function($pertanyaan) {
            $jawabanByDosen = $pertanyaan->jawaban
                ->groupBy('nidn')
                ->map(function($jawaban, $nidn) use ($pertanyaan) {
                    $dosen = $jawaban->first()?->dosen;
                    
                    if ($pertanyaan->isRating()) {
                        return [
                            'dosen' => $dosen,
                            'avg_rating' => round($jawaban->avg('nilai_rating'), 2),
                            'total_responses' => $jawaban->whereNotNull('nilai_rating')->count(),
                        ];
                    } else {
                        return [
                            'dosen' => $dosen,
                            'total_responses' => $jawaban->whereNotNull('jawaban_text')->count(),
                        ];
                    }
                });

            return [
                'pertanyaan' => $pertanyaan,
                'by_dosen' => $jawabanByDosen,
            ];
        });

        return view('pimpinan.summary.pertanyaan', compact('periode', 'pertanyaanSummary'));
    }
}
