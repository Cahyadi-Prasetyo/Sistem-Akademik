<?php

namespace App\Repositories\Contracts;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Collection;

interface DosenRepositoryInterface extends BaseRepositoryInterface
{
    public function findByNidn(string $nidn): ?Dosen;
    public function getWithJadwal(string $nidn): ?Dosen;
    public function getJadwalHariIni(string $nidn): Collection;
    public function getTotalMahasiswa(string $nidn): int;
    public function search(string $keyword): Collection;
}
