<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\EpbmPeriode;
use App\Models\EpbmPertanyaan;
use Illuminate\Support\Facades\Hash;

class Phase2Seeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Pimpinan
        User::create([
            'nama_user' => 'Rektor Universitas',
            'kode' => 'REKTOR01',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
        ]);

        // 2. Create Fakultas, Jurusan, Prodi
        $fakultasTeknik = Fakultas::create(['nama_fakultas' => 'Fakultas Teknik']);
        $fakultasEkonomi = Fakultas::create(['nama_fakultas' => 'Fakultas Ekonomi']);

        // Jurusan & Prodi Teknik
        $jurusanInformatika = Jurusan::create([
            'fakultas_id' => $fakultasTeknik->id,
            'nama_jurusan' => 'Teknik Informatika'
        ]);
        
        // Create Kaprodi User
        $kaprodiTI = User::create([
            'nama_user' => 'Kaprodi TI',
            'kode' => 'KAPRODI01',
            'password' => Hash::make('password'),
            'role' => 'kaprodi',
        ]);

        $prodiTI = Prodi::create([
            'jurusan_id' => $jurusanInformatika->id,
            'nama_prodi' => 'S1 Informatika',
            'kaprodi_user_id' => $kaprodiTI->id
        ]);

        // 3. Create EPBM Data
        $periode = EpbmPeriode::create([
            'jurusan_id' => $jurusanInformatika->id,
            'nama_periode' => 'Evaluasi Semester Ganjil 2024/2025',
            'tanggal_mulai' => now()->subDays(7),
            'tanggal_selesai' => now()->addDays(14),
            'is_active' => true,
        ]);

        // Create Default Questions
        $questions = [
            ['urutan' => 1, 'pertanyaan' => 'Dosen menyampaikan materi dengan jelas', 'jenis' => 'rating'],
            ['urutan' => 2, 'pertanyaan' => 'Dosen datang tepat waktu', 'jenis' => 'rating'],
            ['urutan' => 3, 'pertanyaan' => 'Kesesuaian materi dengan RPS', 'jenis' => 'rating'],
            ['urutan' => 4, 'pertanyaan' => 'Dosen memberikan kesempatan bertanya', 'jenis' => 'rating'],
            ['urutan' => 5, 'pertanyaan' => 'Saran dan masukan untuk dosen', 'jenis' => 'text'],
        ];

        foreach ($questions as $q) {
            EpbmPertanyaan::create([
                'epbm_periode_id' => $periode->id,
                'urutan' => $q['urutan'],
                'pertanyaan' => $q['pertanyaan'],
                'jenis' => $q['jenis'],
            ]);
        }
        
        $this->command->info('Phase 2 Seeder ran successfully: Created Pimpinan, Org Structure, and EPBM Data.');
    }
}
