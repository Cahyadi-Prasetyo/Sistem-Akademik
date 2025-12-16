<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByKode(string $kode): ?User;
    public function getByRole(string $role);
    public function createWithPassword(array $data): User;
    public function updatePassword(int $id, string $password): bool;
}
