<?php

namespace App\Repositories\Contracts;

use App\Models\RencanaStudi;
use Illuminate\Database\Eloquent\Collection;

interface RencanaStudiRepositoryInterface extends BaseRepositoryInterface
{
    public function getByMahasiswa(string $nim): Collection;
    public function getByMahasiswaWithDetails(string $nim): Collection;
    public function addToKrs(string $nim, int $jadwalId): RencanaStudi;
    public function removeFromKrs(string $nim, int $jadwalId): bool;
    public function submitKrs(string $nim): bool;
    public function getTotalSksKrs(string $nim): int;
    public function checkScheduleConflict(string $nim, string $hari, string $jam): bool;
    public function checkDuplicateMataKuliah(string $nim, int $mataKuliahId): bool;
    public function getByJadwal(int $jadwalId): Collection;
    public function updateNilai(int $id, float $nilaiAngka, string $nilaiHuruf): bool;
}
