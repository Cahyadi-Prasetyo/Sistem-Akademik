<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    protected $fillable = [
        'fakultas_id',
        'nama_jurusan',
    ];

    /**
     * Get the fakultas
     */
    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    /**
     * Get all prodi in this jurusan
     */
    public function prodi(): HasMany
    {
        return $this->hasMany(Prodi::class, 'jurusan_id');
    }

    /**
     * Get all EPBM periode for this jurusan
     */
    public function epbmPeriode(): HasMany
    {
        return $this->hasMany(EpbmPeriode::class, 'jurusan_id');
    }
}
