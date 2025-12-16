<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EpbmPeriode extends Model
{
    protected $table = 'epbm_periode';

    protected $fillable = [
        'jurusan_id',
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the jurusan
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    /**
     * Get all pertanyaan in this periode
     */
    public function pertanyaan(): HasMany
    {
        return $this->hasMany(EpbmPertanyaan::class, 'epbm_periode_id')->orderBy('urutan');
    }

    /**
     * Check if periode is currently active based on date
     */
    public function isCurrentlyActive(): bool
    {
        $today = now()->toDateString();
        return $this->is_active && 
               $this->tanggal_mulai <= $today && 
               $this->tanggal_selesai >= $today;
    }
}
