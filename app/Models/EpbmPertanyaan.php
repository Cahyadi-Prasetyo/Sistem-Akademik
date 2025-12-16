<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EpbmPertanyaan extends Model
{
    protected $table = 'epbm_pertanyaan';

    protected $fillable = [
        'epbm_periode_id',
        'urutan',
        'pertanyaan',
        'jenis',
    ];

    /**
     * Get the periode
     */
    public function periode(): BelongsTo
    {
        return $this->belongsTo(EpbmPeriode::class, 'epbm_periode_id');
    }

    /**
     * Get all jawaban for this pertanyaan
     */
    public function jawaban(): HasMany
    {
        return $this->hasMany(EpbmJawaban::class, 'epbm_pertanyaan_id');
    }

    /**
     * Check if this is a rating question
     */
    public function isRating(): bool
    {
        return $this->jenis === 'rating';
    }

    /**
     * Check if this is a text question
     */
    public function isText(): bool
    {
        return $this->jenis === 'text';
    }
}
