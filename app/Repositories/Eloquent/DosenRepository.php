<?php

namespace App\Repositories\Eloquent;

use App\Models\Dosen;
use App\Repositories\Contracts\DosenRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DosenRepository extends BaseRepository implements DosenRepositoryInterface
{
    public function __construct(Dosen $model)
    {
        parent::__construct($model);
    }

    public function findByNidn(string $nidn): ?Dosen
    {
        return $this->model->find($nidn);
    }

    public function getWithJadwal(string $nidn): ?Dosen
    {
        return $this->model->with(['jadwal.mataKuliah', 'jadwal.ruangan'])
            ->find($nidn);
    }

    public function getJadwalHariIni(string $nidn): Collection
    {
        $hariIndonesia = $this->getHariIndonesia();
        
        return $this->model->find($nidn)
            ?->jadwal()
            ->with(['mataKuliah', 'ruangan'])
            ->where('hari', $hariIndonesia)
            ->orderBy('jam')
            ->get() ?? collect();
    }

    public function getTotalMahasiswa(string $nidn): int
    {
        $dosen = $this->model->find($nidn);
        
        if (!$dosen) {
            return 0;
        }

        return $dosen->jadwal()
            ->withCount('rencanaStudi')
            ->get()
            ->sum('rencana_studi_count');
    }

    public function search(string $keyword): Collection
    {
        return $this->model->where('nidn', 'like', "%{$keyword}%")
            ->orWhere('nama', 'like', "%{$keyword}%")
            ->get();
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
}
