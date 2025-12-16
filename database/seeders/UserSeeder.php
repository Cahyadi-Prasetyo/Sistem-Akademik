<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        \App\Models\User::create([
            'nama_user' => 'Administrator',
            'kode' => 'admin',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        // Dosen users (password = NIDN)
        $dosenList = \App\Models\Dosen::all();
        foreach ($dosenList as $dosen) {
            \App\Models\User::create([
                'nama_user' => $dosen->nama,
                'kode' => $dosen->nidn,
                'password' => Hash::make($dosen->nidn),
                'role' => 'dosen',
            ]);
        }

        // Mahasiswa users (password = NIM)
        $mahasiswaList = \App\Models\Mahasiswa::all();
        foreach ($mahasiswaList as $mhs) {
            \App\Models\User::create([
                'nama_user' => $mhs->nama,
                'kode' => $mhs->nim,
                'password' => Hash::make($mhs->nim),
                'role' => 'mahasiswa',
            ]);
        }
    }
}
