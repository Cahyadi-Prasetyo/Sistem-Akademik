<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Base tables first (no dependencies)
            NilaiMutuSeeder::class,
            MahasiswaSeeder::class,
            DosenSeeder::class,
            MataKuliahSeeder::class,
            RuanganSeeder::class,
            
            // Tables with foreign keys
            JadwalSeeder::class,
            
            // User accounts (depends on mahasiswa and dosen)
            UserSeeder::class,
            
            // KRS data (depends on mahasiswa and jadwal)
            RencanaStudiSeeder::class,

            // Phase 2 Features (Org Structure, EPBM, Roles)
            Phase2Seeder::class,
        ]);
    }
}
