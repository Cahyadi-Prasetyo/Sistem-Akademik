<?php

namespace App\Repositories\Contracts;

interface MataKuliahRepositoryInterface extends BaseRepositoryInterface
{
    public function findByKode(string $kode);
    public function search(string $keyword);
}
