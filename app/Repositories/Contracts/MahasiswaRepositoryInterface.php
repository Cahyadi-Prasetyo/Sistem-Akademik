<?php

namespace App\Repositories\Contracts;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Collection;

interface MahasiswaRepositoryInterface extends BaseRepositoryInterface
{
    public function findByNim(string $nim): ?Mahasiswa;
    public function getWithKrs(string $nim): ?Mahasiswa;
    public function calculateIpk(string $nim): float;
    public function getTotalSks(string $nim): int;
    public function search(string $keyword): Collection;
}
