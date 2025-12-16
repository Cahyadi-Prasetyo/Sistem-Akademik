<?php

namespace App\Repositories\Eloquent;

use App\Models\NilaiMutu;
use App\Models\RencanaStudi;
use App\Repositories\Contracts\RencanaStudiRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RencanaStudiRepository extends BaseRepository implements RencanaStudiRepositoryInterface
{
    public function __construct(RencanaStudi $model)
    {
        parent::__construct($model);
    }

    public function getByMahasiswa(string $nim): Collection
    {
        return $this->model->where('nim', $nim)->get();
    }

    public function getByMahasiswaWithDetails(string $nim): Collection
    {
        return $this->model->with(['jadwal.mataKuliah', 'jadwal.dosen', 'jadwal.ruangan'])
            ->where('nim', $nim)
            ->get();
    }

    public function addToKrs(string $nim, int $jadwalId): RencanaStudi
    {
        return $this->model->create([
            'nim' => $nim,
            'jadwal_id' => $jadwalId,
            'status' => 'draft',
        ]);
    }

    public function removeFromKrs(string $nim, int $jadwalId): bool
    {
        return $this->model->where('nim', $nim)
            ->where('jadwal_id', $jadwalId)
            ->where('status', 'draft')
            ->delete() > 0;
    }

    public function submitKrs(string $nim): bool
    {
        return $this->model->where('nim', $nim)
            ->where('status', 'draft')
            ->update(['status' => 'submitted']) > 0;
    }

    public function getTotalSksKrs(string $nim): int
    {
        return $this->model->with('jadwal.mataKuliah')
            ->where('nim', $nim)
            ->get()
            ->sum(function ($krs) {
                return $krs->jadwal?->mataKuliah?->sks ?? 0;
            });
    }

    public function checkScheduleConflict(string $nim, string $hari, string $jam): bool
    {
        $existingKrs = $this->model->with('jadwal')
            ->where('nim', $nim)
            ->get();

        foreach ($existingKrs as $krs) {
            if ($krs->jadwal && $krs->jadwal->hari === $hari) {
                if ($this->isTimeOverlap($krs->jadwal->jam, $jam)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function checkDuplicateMataKuliah(string $nim, int $mataKuliahId): bool
    {
        return $this->model->with('jadwal')
            ->where('nim', $nim)
            ->whereHas('jadwal', function ($query) use ($mataKuliahId) {
                $query->where('mata_kuliah_id', $mataKuliahId);
            })
            ->exists();
    }

    public function getByJadwal(int $jadwalId): Collection
    {
        return $this->model->with('mahasiswa')
            ->where('jadwal_id', $jadwalId)
            ->get();
    }

    public function updateNilai(int $id, float $nilaiAngka, string $nilaiHuruf): bool
    {
        return $this->model->where('id', $id)
            ->update([
                'nilai_angka' => $nilaiAngka,
                'nilai_huruf' => $nilaiHuruf,
            ]) > 0;
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
