<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruangan = [
            ['nama_ruangan' => 'Lab Komputer 1'],
            ['nama_ruangan' => 'Lab Komputer 2'],
            ['nama_ruangan' => 'Lab Komputer 3'],
            ['nama_ruangan' => 'Ruang Kelas A101'],
            ['nama_ruangan' => 'Ruang Kelas A102'],
            ['nama_ruangan' => 'Ruang Kelas B101'],
            ['nama_ruangan' => 'Ruang Kelas B102'],
            ['nama_ruangan' => 'Auditorium'],
        ];

        foreach ($ruangan as $rng) {
            \App\Models\Ruangan::create($rng);
        }
    }
}
