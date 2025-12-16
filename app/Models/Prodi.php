<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prodi extends Model
{
    protected $table = 'prodi';

    protected $fillable = [
        'jurusan_id',
        'nama_prodi',
        'kaprodi_user_id',
    ];

    /**
     * Get the jurusan
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    /**
     * Get the kaprodi user
     */
    public function kaprodi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kaprodi_user_id');
    }
}
