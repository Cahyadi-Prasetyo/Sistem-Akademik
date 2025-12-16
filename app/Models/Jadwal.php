<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jadwal extends Model
{
    protected $table = 'jadwal';

    protected $fillable = [
        'nama_kelas',
        'mata_kuliah_id',
        'ruangan_id',
        'hari',
        'jam',
    ];

    /**
     * Get the mata kuliah for this jadwal
     */
    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Get the ruangan for this jadwal
     */
    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    /**
     * Get all dosen for this jadwal (team teaching)
     */
    public function dosen(): BelongsToMany
    {
        return $this->belongsToMany(Dosen::class, 'jadwal_dosen', 'jadwal_id', 'nidn', 'id', 'nidn')
            ->withPivot('is_koordinator')
            ->withTimestamps();
    }

    /**
     * Get the koordinator dosen
     */
    public function koordinator()
    {
        return $this->dosen()->wherePivot('is_koordinator', true)->first();
    }

    /**
     * Get all rencana studi for this jadwal
     */
    public function rencanaStudi(): HasMany
    {
        return $this->hasMany(RencanaStudi::class, 'jadwal_id');
    }

    /**
     * Get the time range as array [start, end]
     */
    public function getTimeRangeAttribute(): array
    {
        $times = explode('-', $this->jam);
        return [
            'start' => trim($times[0] ?? '00:00'),
            'end' => trim($times[1] ?? '00:00'),
        ];
    }
}
