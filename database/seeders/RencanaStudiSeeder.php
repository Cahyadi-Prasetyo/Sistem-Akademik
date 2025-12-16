<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RencanaStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample KRS for mahasiswa 2021001 (Ahmad Fadli) - submitted with grades
        $krs2021001 = [
            ['nim' => '2021001', 'jadwal_id' => 1, 'nilai_angka' => 85, 'nilai_huruf' => 'A', 'status' => 'submitted'],
            ['nim' => '2021001', 'jadwal_id' => 3, 'nilai_angka' => 78, 'nilai_huruf' => 'B', 'status' => 'submitted'],
            ['nim' => '2021001', 'jadwal_id' => 4, 'nilai_angka' => 82, 'nilai_huruf' => 'A-', 'status' => 'submitted'],
            ['nim' => '2021001', 'jadwal_id' => 7, 'nilai_angka' => 75, 'nilai_huruf' => 'B', 'status' => 'submitted'],
            ['nim' => '2021001', 'jadwal_id' => 10, 'nilai_angka' => 88, 'nilai_huruf' => 'A', 'status' => 'submitted'],
        ];

        // Sample KRS for mahasiswa 2021002 (Siti Nurhaliza) - draft, no grades yet
        $krs2021002 = [
            ['nim' => '2021002', 'jadwal_id' => 2, 'nilai_angka' => null, 'nilai_huruf' => null, 'status' => 'draft'],
            ['nim' => '2021002', 'jadwal_id' => 5, 'nilai_angka' => null, 'nilai_huruf' => null, 'status' => 'draft'],
            ['nim' => '2021002', 'jadwal_id' => 8, 'nilai_angka' => null, 'nilai_huruf' => null, 'status' => 'draft'],
            ['nim' => '2021002', 'jadwal_id' => 11, 'nilai_angka' => null, 'nilai_huruf' => null, 'status' => 'draft'],
        ];

        // Sample KRS for mahasiswa 2022001 (Fitri Handayani) - submitted, partial grades
        $krs2022001 = [
            ['nim' => '2022001', 'jadwal_id' => 1, 'nilai_angka' => 90, 'nilai_huruf' => 'A', 'status' => 'submitted'],
            ['nim' => '2022001', 'jadwal_id' => 4, 'nilai_angka' => 72, 'nilai_huruf' => 'B', 'status' => 'submitted'],
            ['nim' => '2022001', 'jadwal_id' => 7, 'nilai_angka' => null, 'nilai_huruf' => null, 'status' => 'submitted'],
            ['nim' => '2022001', 'jadwal_id' => 13, 'nilai_angka' => 68, 'nilai_huruf' => 'B-', 'status' => 'submitted'],
        ];

        $allKrs = array_merge($krs2021001, $krs2021002, $krs2022001);

        foreach ($allKrs as $krs) {
            \App\Models\RencanaStudi::create($krs);
        }
    }
}
