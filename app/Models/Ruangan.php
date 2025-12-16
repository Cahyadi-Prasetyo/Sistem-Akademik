<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruangan extends Model
{
    protected $table = 'ruangan';

    protected $fillable = [
        'nama_ruangan',
    ];

    /**
     * Get all jadwal in this ruangan
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'ruangan_id');
    }
}
