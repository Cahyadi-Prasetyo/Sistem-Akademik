<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MahasiswaRepositoryInterface;
use App\Repositories\Contracts\DosenRepositoryInterface;
use App\Repositories\Contracts\JadwalRepositoryInterface;
use App\Repositories\Contracts\RuanganRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class DashboardController extends Controller
{
    protected MahasiswaRepositoryInterface $mahasiswaRepository;
    protected DosenRepositoryInterface $dosenRepository;
    protected JadwalRepositoryInterface $jadwalRepository;
    protected RuanganRepositoryInterface $ruanganRepository;
    protected UserRepositoryInterface $userRepository;

    public function __construct(
        MahasiswaRepositoryInterface $mahasiswaRepository,
        DosenRepositoryInterface $dosenRepository,
        JadwalRepositoryInterface $jadwalRepository,
        RuanganRepositoryInterface $ruanganRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->mahasiswaRepository = $mahasiswaRepository;
        $this->dosenRepository = $dosenRepository;
        $this->jadwalRepository = $jadwalRepository;
        $this->ruanganRepository = $ruanganRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $statistics = [
            'total_mahasiswa' => $this->mahasiswaRepository->getAll()->count(),
            'total_dosen' => $this->dosenRepository->getAll()->count(),
            'total_jadwal' => $this->jadwalRepository->getAll()->count(),
            'total_ruangan' => $this->ruanganRepository->getAll()->count(),
            'total_users' => $this->userRepository->getAll()->count(),
        ];

        $recentMahasiswa = $this->mahasiswaRepository->getAll()->take(5);
        $recentDosen = $this->dosenRepository->getAll()->take(5);
        $recentJadwal = $this->jadwalRepository->getWithRelations()->take(5);

        return view('admin.dashboard', compact('statistics', 'recentMahasiswa', 'recentDosen', 'recentJadwal'));
    }
}
