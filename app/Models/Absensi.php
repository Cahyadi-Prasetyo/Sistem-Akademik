<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'rencana_studi_id',
        'pertemuan_ke',
        'tanggal',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get the rencana studi
     */
    public function rencanaStudi(): BelongsTo
    {
        return $this->belongsTo(RencanaStudi::class, 'rencana_studi_id');
    }

    /**
     * Check if student is present
     */
    public function isHadir(): bool
    {
        return $this->status === 'hadir';
    }

    /**
     * Check if student is absent without permission
     */
    public function isAlpha(): bool
    {
        return $this->status === 'alpha';
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha',
            default => '-',
        };
    }
}
