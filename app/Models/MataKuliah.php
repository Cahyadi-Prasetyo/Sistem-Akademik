<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode_mata_kuliah',
        'nama_mata_kuliah',
        'sks',
        'kategori',
    ];

    /**
     * Get all jadwal for this mata kuliah
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'mata_kuliah_id');
    }
}
