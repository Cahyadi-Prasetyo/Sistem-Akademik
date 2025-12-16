<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EpbmJawaban extends Model
{
    protected $table = 'epbm_jawaban';

    protected $fillable = [
        'epbm_pertanyaan_id',
        'rencana_studi_id',
        'nidn',
        'nilai_rating',
        'jawaban_text',
    ];

    /**
     * Get the pertanyaan
     */
    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(EpbmPertanyaan::class, 'epbm_pertanyaan_id');
    }

    /**
     * Get the rencana studi
     */
    public function rencanaStudi(): BelongsTo
    {
        return $this->belongsTo(RencanaStudi::class, 'rencana_studi_id');
    }

    /**
     * Get the dosen being evaluated
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'nidn', 'nidn');
    }
}
