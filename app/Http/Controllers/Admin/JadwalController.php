<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\JadwalRepositoryInterface;
use App\Repositories\Contracts\MataKuliahRepositoryInterface;
use App\Repositories\Contracts\RuanganRepositoryInterface;
use App\Repositories\Contracts\DosenRepositoryInterface;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    protected JadwalRepositoryInterface $repository;
    protected MataKuliahRepositoryInterface $mataKuliahRepository;
    protected RuanganRepositoryInterface $ruanganRepository;
    protected DosenRepositoryInterface $dosenRepository;

    public function __construct(
        JadwalRepositoryInterface $repository,
        MataKuliahRepositoryInterface $mataKuliahRepository,
        RuanganRepositoryInterface $ruanganRepository,
        DosenRepositoryInterface $dosenRepository
    ) {
        $this->repository = $repository;
        $this->mataKuliahRepository = $mataKuliahRepository;
        $this->ruanganRepository = $ruanganRepository;
        $this->dosenRepository = $dosenRepository;
    }

    public function index()
    {
        $jadwal = $this->repository->getWithRelations();

        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $mataKuliah = $this->mataKuliahRepository->getAll();
        $ruangan = $this->ruanganRepository->getAll();
        $dosen = $this->dosenRepository->getAll();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('admin.jadwal.create', compact('mataKuliah', 'ruangan', 'dosen', 'hariList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'ruangan_id' => 'required|exists:ruangan,id',
            'nidn' => 'required|exists:dosen,nidn',
            'hari' => 'required|string',
            'jam' => 'required|string',
        ]);

        // Check for conflicts
        if ($this->repository->checkRoomConflict($request->ruangan_id, $request->hari, $request->jam)) {
            return back()->withInput()->with('error', 'Ruangan sudah digunakan pada waktu tersebut');
        }

        if ($this->repository->checkDosenConflict($request->nidn, $request->hari, $request->jam)) {
            return back()->withInput()->with('error', 'Dosen sudah mengajar pada waktu tersebut');
        }

        $this->repository->create($request->only([
            'nama_kelas', 'mata_kuliah_id', 'ruangan_id', 'nidn', 'hari', 'jam'
        ]));

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function show(int $id)
    {
        $jadwal = $this->repository->findById($id);

        if (!$jadwal) {
            return redirect()->route('admin.jadwal.index')
                ->with('error', 'Jadwal tidak ditemukan');
        }

        $jadwal->load(['mataKuliah', 'ruangan', 'dosen', 'rencanaStudi.mahasiswa']);

        return view('admin.jadwal.show', compact('jadwal'));
    }

    public function edit(int $id)
    {
        $jadwal = $this->repository->findById($id);

        if (!$jadwal) {
            return redirect()->route('admin.jadwal.index')
                ->with('error', 'Jadwal tidak ditemukan');
        }

        $mataKuliah = $this->mataKuliahRepository->getAll();
        $ruangan = $this->ruanganRepository->getAll();
        $dosen = $this->dosenRepository->getAll();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('admin.jadwal.edit', compact('jadwal', 'mataKuliah', 'ruangan', 'dosen', 'hariList'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'ruangan_id' => 'required|exists:ruangan,id',
            'nidn' => 'required|exists:dosen,nidn',
            'hari' => 'required|string',
            'jam' => 'required|string',
        ]);

        // Check for conflicts (exclude current jadwal)
        if ($this->repository->checkRoomConflict($request->ruangan_id, $request->hari, $request->jam, $id)) {
            return back()->withInput()->with('error', 'Ruangan sudah digunakan pada waktu tersebut');
        }

        if ($this->repository->checkDosenConflict($request->nidn, $request->hari, $request->jam, $id)) {
            return back()->withInput()->with('error', 'Dosen sudah mengajar pada waktu tersebut');
        }

        $this->repository->update($id, $request->only([
            'nama_kelas', 'mata_kuliah_id', 'ruangan_id', 'nidn', 'hari', 'jam'
        ]));

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }

    public function checkConflict(Request $request)
    {
        $roomConflict = $this->repository->checkRoomConflict(
            $request->ruangan_id,
            $request->hari,
            $request->jam,
            $request->exclude_id
        );

        $dosenConflict = $this->repository->checkDosenConflict(
            $request->nidn,
            $request->hari,
            $request->jam,
            $request->exclude_id
        );

        return response()->json([
            'room_conflict' => $roomConflict,
            'dosen_conflict' => $dosenConflict,
        ]);
    }
}
