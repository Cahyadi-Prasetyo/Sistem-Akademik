<?php

namespace App\Repositories\Contracts;

interface RuanganRepositoryInterface extends BaseRepositoryInterface
{
    public function search(string $keyword);
}
