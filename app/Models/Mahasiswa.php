<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'nama',
    ];

    /**
     * Get all rencana studi (KRS) for this mahasiswa
     */
    public function rencanaStudi(): HasMany
    {
        return $this->hasMany(RencanaStudi::class, 'nim', 'nim');
    }

    /**
     * Get the user account for this mahasiswa
     */
    public function user()
    {
        return $this->hasOne(User::class, 'kode', 'nim');
    }
}
