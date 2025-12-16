<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(int|string $id);
    public function create(array $data);
    public function update(int|string $id, array $data): bool;
    public function delete(int|string $id): bool;
}
