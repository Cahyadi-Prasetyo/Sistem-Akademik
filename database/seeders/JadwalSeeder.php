<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwal = [
            // Senin
            ['nama_kelas' => 'IF-A', 'mata_kuliah_id' => 1, 'ruangan_id' => 1, 'nidn' => '0001018501', 'hari' => 'Senin', 'jam' => '08:00-10:30'],
            ['nama_kelas' => 'IF-B', 'mata_kuliah_id' => 1, 'ruangan_id' => 2, 'nidn' => '0001018501', 'hari' => 'Senin', 'jam' => '10:30-13:00'],
            ['nama_kelas' => 'IF-A', 'mata_kuliah_id' => 2, 'ruangan_id' => 3, 'nidn' => '0002028502', 'hari' => 'Senin', 'jam' => '13:00-15:30'],
            
            // Selasa
            ['nama_kelas' => 'IF-A', 'mata_kuliah_id' => 3, 'ruangan_id' => 1, 'nidn' => '0003038503', 'hari' => 'Selasa', 'jam' => '08:00-10:30'],
            ['nama_kelas' => 'IF-B', 'mata_kuliah_id' => 3, 'ruangan_id' => 2, 'nidn' => '0003038503', 'hari' => 'Selasa', 'jam' => '10:30-13:00'],
            ['nama_kelas' => 'IF-A', 'mata_kuliah_id' => 4, 'ruangan_id' => 3, 'nidn' => '0004048504', 'hari' => 'Selasa', 'jam' => '13:00-15:30'],
            
            // Rabu  
            ['nama_kelas' => 'IF-A', 'mata_kuliah_id' => 5, 'ruangan_id' => 4, 'nidn' => '0005058505', 'hari' => 'Rabu', 'jam' => '08:00-10:30'],
            ['nama_kelas' => 'IF-B', 'mata_kuliah_id' => 5, 'ruangan_id' => 5, 'nidn' => '0005058505', 'hari' => 'Rabu', 'jam' => '10:30-13:00'],
            ['nama_kelas' => 'IF-A', 'mata_kuliah_id' => 6, 'ruangan_id' => 1, 'nidn' => '0001018501', 'hari' => 'Rabu', 'jam' => '13:00-15:30'],
            
            // Kamis
            ['nama_kelas' => 'IF-A', 'mata_kuliah_id' => 7, 'ruangan_id' => 2, 'nidn' => '0002028502', 'hari' => 'Kamis', 'jam' => '08:00-10:30'],
            ['nama_kelas' => 'IF-B', 'mata_kuliah_id' => 7, 'ruangan_id' => 3, 'nidn' => '0002028502', 'hari' => 'Kamis', 'jam' => '10:30-13:00'],
            ['nama_kelas' => 'IF-A', 'mata_kuliah_id' => 8, 'ruangan_id' => 4, 'nidn' => '0003038503', 'hari' => 'Kamis', 'jam' => '13:00-15:30'],
            
            // Jumat
            ['nama_kelas' => 'IF-A', 'mata_kuliah_id' => 9, 'ruangan_id' => 5, 'nidn' => '0004048504', 'hari' => 'Jumat', 'jam' => '08:00-09:40'],
            ['nama_kelas' => 'IF-A', 'mata_kuliah_id' => 10, 'ruangan_id' => 1, 'nidn' => '0005058505', 'hari' => 'Jumat', 'jam' => '10:00-12:30'],
        ];

        foreach ($jadwal as $jdw) {
            $nidn = $jdw['nidn'];
            unset($jdw['nidn']);

            $newJadwal = \App\Models\Jadwal::create($jdw);
            // Attach dosen as koordinator
            $newJadwal->dosen()->attach($nidn, ['is_koordinator' => true]);
        }
    }
}
