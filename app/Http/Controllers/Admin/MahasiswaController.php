<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MahasiswaRepositoryInterface;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    protected MahasiswaRepositoryInterface $repository;

    public function __construct(MahasiswaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            $mahasiswa = $this->repository->search($search);
        } else {
            $mahasiswa = $this->repository->getAll();
        }

        return view('admin.mahasiswa.index', compact('mahasiswa', 'search'));
    }

    public function create()
    {
        return view('admin.mahasiswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'nama' => 'required|string|max:100',
        ]);

        $this->repository->create($request->only(['nim', 'nama']));

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    public function show(string $nim)
    {
        $mahasiswa = $this->repository->getWithKrs($nim);

        if (!$mahasiswa) {
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Mahasiswa tidak ditemukan');
        }

        $ipk = $this->repository->calculateIpk($nim);
        $totalSks = $this->repository->getTotalSks($nim);

        return view('admin.mahasiswa.show', compact('mahasiswa', 'ipk', 'totalSks'));
    }

    public function edit(string $nim)
    {
        $mahasiswa = $this->repository->findByNim($nim);

        if (!$mahasiswa) {
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Mahasiswa tidak ditemukan');
        }

        return view('admin.mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, string $nim)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        $this->repository->update($nim, $request->only(['nama']));

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil diperbarui');
    }

    public function destroy(string $nim)
    {
        $this->repository->delete($nim);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil dihapus');
    }
}
