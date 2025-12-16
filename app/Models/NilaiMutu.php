<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMutu extends Model
{
    protected $table = 'nilai_mutu';
    protected $primaryKey = 'nilai_huruf';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nilai_huruf',
        'nilai_mutu',
    ];

    /**
     * Convert numeric grade to letter grade
     */
    public static function getLetterGrade(float $nilai): string
    {
        if ($nilai >= 85) return 'A';
        if ($nilai >= 80) return 'A-';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 65) return 'B-';
        if ($nilai >= 55) return 'C';
        if ($nilai >= 40) return 'D';
        return 'E';
    }

    /**
     * Get mutu value for a letter grade
     */
    public static function getMutuValue(string $nilaiHuruf): float
    {
        $mutuMap = [
            'A' => 4.00,
            'A-' => 3.50,
            'B' => 3.00,
            'B-' => 2.50,
            'C' => 2.00,
            'D' => 1.00,
            'E' => 0.00,
        ];

        return $mutuMap[$nilaiHuruf] ?? 0.00;
    }
}
