<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByKode(string $kode): ?User
    {
        return $this->model->where('kode', $kode)->first();
    }

    public function getByRole(string $role)
    {
        return $this->model->where('role', $role)->get();
    }

    public function createWithPassword(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->model->create($data);
    }

    public function updatePassword(int $id, string $password): bool
    {
        return $this->model->where('id', $id)
            ->update(['password' => Hash::make($password)]) > 0;
    }
}
