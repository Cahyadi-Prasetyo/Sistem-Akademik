<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswa = [
            ['nim' => '2021001', 'nama' => 'Ahmad Fadli'],
            ['nim' => '2021002', 'nama' => 'Siti Nurhaliza'],
            ['nim' => '2021003', 'nama' => 'Budi Santoso'],
            ['nim' => '2021004', 'nama' => 'Dewi Lestari'],
            ['nim' => '2021005', 'nama' => 'Eko Prasetyo'],
            ['nim' => '2022001', 'nama' => 'Fitri Handayani'],
            ['nim' => '2022002', 'nama' => 'Gunawan Setiawan'],
            ['nim' => '2022003', 'nama' => 'Hani Susanti'],
            ['nim' => '2022004', 'nama' => 'Irfan Hakim'],
            ['nim' => '2022005', 'nama' => 'Jasmine Putri'],
        ];

        foreach ($mahasiswa as $mhs) {
            \App\Models\Mahasiswa::create($mhs);
        }
    }
}
