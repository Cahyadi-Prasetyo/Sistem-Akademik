<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataKuliah = [
            ['kode_mata_kuliah' => 'IF101', 'nama_mata_kuliah' => 'Algoritma dan Pemrograman', 'sks' => 3],
            ['kode_mata_kuliah' => 'IF102', 'nama_mata_kuliah' => 'Struktur Data', 'sks' => 3],
            ['kode_mata_kuliah' => 'IF103', 'nama_mata_kuliah' => 'Basis Data', 'sks' => 3],
            ['kode_mata_kuliah' => 'IF104', 'nama_mata_kuliah' => 'Pemrograman Web', 'sks' => 3],
            ['kode_mata_kuliah' => 'IF105', 'nama_mata_kuliah' => 'Jaringan Komputer', 'sks' => 3],
            ['kode_mata_kuliah' => 'IF201', 'nama_mata_kuliah' => 'Pemrograman Berorientasi Objek', 'sks' => 3],
            ['kode_mata_kuliah' => 'IF202', 'nama_mata_kuliah' => 'Rekayasa Perangkat Lunak', 'sks' => 3],
            ['kode_mata_kuliah' => 'IF203', 'nama_mata_kuliah' => 'Kecerdasan Buatan', 'sks' => 3],
            ['kode_mata_kuliah' => 'IF204', 'nama_mata_kuliah' => 'Keamanan Sistem Informasi', 'sks' => 2],
            ['kode_mata_kuliah' => 'IF205', 'nama_mata_kuliah' => 'Mobile Programming', 'sks' => 3],
        ];

        foreach ($mataKuliah as $mk) {
            \App\Models\MataKuliah::create($mk);
        }
    }
}
