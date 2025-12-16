<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dosen extends Model
{
    protected $table = 'dosen';
    protected $primaryKey = 'nidn';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nidn',
        'nama',
    ];

    /**
     * Get all jadwal taught by this dosen
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'nidn', 'nidn');
    }

    /**
     * Get the user account for this dosen
     */
    public function user()
    {
        return $this->hasOne(User::class, 'kode', 'nidn');
    }
}
