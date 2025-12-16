<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalDosen extends Model
{
    protected $table = 'jadwal_dosen';

    protected $fillable = [
        'jadwal_id',
        'nidn',
        'is_koordinator',
    ];

    protected $casts = [
        'is_koordinator' => 'boolean',
    ];

    /**
     * Get the jadwal
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    /**
     * Get the dosen
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'nidn', 'nidn');
    }
}
