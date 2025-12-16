<?php

namespace App\Repositories\Eloquent;

use App\Models\Ruangan;
use App\Repositories\Contracts\RuanganRepositoryInterface;

class RuanganRepository extends BaseRepository implements RuanganRepositoryInterface
{
    public function __construct(Ruangan $model)
    {
        parent::__construct($model);
    }

    public function search(string $keyword)
    {
        return $this->model->where('nama_ruangan', 'like', "%{$keyword}%")->get();
    }
}
