<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RencanaStudi extends Model
{
    protected $table = 'rencana_studi';

    protected $fillable = [
        'nim',
        'jadwal_id',
        'nilai_angka',
        'nilai_huruf',
        'status',
    ];

    protected $casts = [
        'nilai_angka' => 'decimal:2',
    ];

    /**
     * Get the mahasiswa for this rencana studi
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    /**
     * Get the jadwal for this rencana studi
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    /**
     * Check if this KRS is submitted (locked)
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if this KRS is still draft (editable)
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Get the mutu value based on nilai_huruf
     */
    public function getMutuAttribute(): float
    {
        if (!$this->nilai_huruf) {
            return 0.00;
        }
        return NilaiMutu::getMutuValue($this->nilai_huruf);
    }
}
