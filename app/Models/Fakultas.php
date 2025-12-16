<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fakultas extends Model
{
    protected $table = 'fakultas';

    protected $fillable = [
        'nama_fakultas',
    ];

    /**
     * Get all jurusan in this fakultas
     */
    public function jurusan(): HasMany
    {
        return $this->hasMany(Jurusan::class, 'fakultas_id');
    }
}
