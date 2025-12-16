<?php

namespace App\Repositories\Contracts;

use App\Models\Jadwal;
use Illuminate\Database\Eloquent\Collection;

interface JadwalRepositoryInterface extends BaseRepositoryInterface
{
    public function getWithRelations(): Collection;
    public function checkRoomConflict(int $ruanganId, string $hari, string $jam, ?int $excludeId = null): bool;
    public function checkDosenConflict(string $nidn, string $hari, string $jam, ?int $excludeId = null): bool;
    public function getByDosen(string $nidn): Collection;
    public function getByHari(string $hari): Collection;
    public function getByMataKuliah(int $mataKuliahId): Collection;
}
