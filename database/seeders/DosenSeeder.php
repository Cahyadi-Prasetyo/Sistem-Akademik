<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosen = [
            ['nidn' => '0001018501', 'nama' => 'Dr. Bambang Sutrisno, M.Kom'],
            ['nidn' => '0002028502', 'nama' => 'Dr. Citra Dewi, M.T'],
            ['nidn' => '0003038503', 'nama' => 'Prof. Darmawan, Ph.D'],
            ['nidn' => '0004048504', 'nama' => 'Dr. Erna Wati, M.Si'],
            ['nidn' => '0005058505', 'nama' => 'Dr. Fajar Hidayat, M.Kom'],
        ];

        foreach ($dosen as $dsn) {
            \App\Models\Dosen::create($dsn);
        }
    }
}
