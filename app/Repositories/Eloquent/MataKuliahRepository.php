<?php

namespace App\Repositories\Eloquent;

use App\Models\MataKuliah;
use App\Repositories\Contracts\MataKuliahRepositoryInterface;

class MataKuliahRepository extends BaseRepository implements MataKuliahRepositoryInterface
{
    public function __construct(MataKuliah $model)
    {
        parent::__construct($model);
    }

    public function findByKode(string $kode)
    {
        return $this->model->where('kode_mata_kuliah', $kode)->first();
    }

    public function search(string $keyword)
    {
        return $this->model->where('kode_mata_kuliah', 'like', "%{$keyword}%")
            ->orWhere('nama_mata_kuliah', 'like', "%{$keyword}%")
            ->get();
    }
}
