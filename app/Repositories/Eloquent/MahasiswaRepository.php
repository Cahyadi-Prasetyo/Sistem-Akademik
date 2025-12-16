<?php

namespace App\Repositories\Eloquent;

use App\Models\Mahasiswa;
use App\Models\NilaiMutu;
use App\Repositories\Contracts\MahasiswaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MahasiswaRepository extends BaseRepository implements MahasiswaRepositoryInterface
{
    public function __construct(Mahasiswa $model)
    {
        parent::__construct($model);
    }

    public function findByNim(string $nim): ?Mahasiswa
    {
        return $this->model->find($nim);
    }

    public function getWithKrs(string $nim): ?Mahasiswa
    {
        return $this->model->with(['rencanaStudi.jadwal.mataKuliah', 'rencanaStudi.jadwal.dosen'])
            ->find($nim);
    }

    public function calculateIpk(string $nim): float
    {
        $mahasiswa = $this->getWithKrs($nim);
        
        if (!$mahasiswa || $mahasiswa->rencanaStudi->isEmpty()) {
            return 0.00;
        }

        $totalMutu = 0;
        $totalSks = 0;

        foreach ($mahasiswa->rencanaStudi as $krs) {
            if ($krs->nilai_huruf && $krs->jadwal && $krs->jadwal->mataKuliah) {
                $sks = $krs->jadwal->mataKuliah->sks;
                $mutu = NilaiMutu::getMutuValue($krs->nilai_huruf);
                
                $totalMutu += ($mutu * $sks);
                $totalSks += $sks;
            }
        }

        if ($totalSks === 0) {
            return 0.00;
        }

        return round($totalMutu / $totalSks, 2);
    }

    public function getTotalSks(string $nim): int
    {
        $mahasiswa = $this->getWithKrs($nim);
        
        if (!$mahasiswa || $mahasiswa->rencanaStudi->isEmpty()) {
            return 0;
        }

        return $mahasiswa->rencanaStudi->sum(function ($krs) {
            return $krs->jadwal?->mataKuliah?->sks ?? 0;
        });
    }

    public function search(string $keyword): Collection
    {
        return $this->model->where('nim', 'like', "%{$keyword}%")
            ->orWhere('nama', 'like', "%{$keyword}%")
            ->get();
    }
}
