<?php

namespace App\Repositories\Eloquent;

use App\Models\Jadwal;
use App\Repositories\Contracts\JadwalRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class JadwalRepository extends BaseRepository implements JadwalRepositoryInterface
{
    public function __construct(Jadwal $model)
    {
        parent::__construct($model);
    }

    public function getWithRelations(): Collection
    {
        return $this->model->with(['mataKuliah', 'ruangan', 'dosen'])->get();
    }

    public function checkRoomConflict(int $ruanganId, string $hari, string $jam, ?int $excludeId = null): bool
    {
        $query = $this->model->where('ruangan_id', $ruanganId)
            ->where('hari', $hari);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existingJadwal = $query->get();

        foreach ($existingJadwal as $jadwal) {
            if ($this->isTimeOverlap($jadwal->jam, $jam)) {
                return true;
            }
        }

        return false;
    }

    public function checkDosenConflict(string $nidn, string $hari, string $jam, ?int $excludeId = null): bool
    {
        $query = $this->model->where('nidn', $nidn)
            ->where('hari', $hari);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $existingJadwal = $query->get();

        foreach ($existingJadwal as $jadwal) {
            if ($this->isTimeOverlap($jadwal->jam, $jam)) {
                return true;
            }
        }

        return false;
    }

    public function getByDosen(string $nidn): Collection
    {
        return $this->model->with(['mataKuliah', 'ruangan'])
            ->where('nidn', $nidn)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam')
            ->get();
    }

    public function getByHari(string $hari): Collection
    {
        return $this->model->with(['mataKuliah', 'ruangan', 'dosen'])
            ->where('hari', $hari)
            ->orderBy('jam')
            ->get();
    }

    public function getByMataKuliah(int $mataKuliahId): Collection
    {
        return $this->model->with(['ruangan', 'dosen'])
            ->where('mata_kuliah_id', $mataKuliahId)
            ->get();
    }

    /**
     * Check if two time ranges overlap
     */
    private function isTimeOverlap(string $time1, string $time2): bool
    {
        [$start1, $end1] = array_map('trim', explode('-', $time1));
        [$start2, $end2] = array_map('trim', explode('-', $time2));

        $start1 = strtotime($start1);
        $end1 = strtotime($end1);
        $start2 = strtotime($start2);
        $end2 = strtotime($end2);

        return ($start1 < $end2) && ($start2 < $end1);
    }
}
