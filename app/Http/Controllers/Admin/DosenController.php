<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\DosenRepositoryInterface;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    protected DosenRepositoryInterface $repository;

    public function __construct(DosenRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            $dosen = $this->repository->search($search);
        } else {
            $dosen = $this->repository->getAll();
        }

        return view('admin.dosen.index', compact('dosen', 'search'));
    }

    public function create()
    {
        return view('admin.dosen.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nidn' => 'required|string|max:20|unique:dosen,nidn',
            'nama' => 'required|string|max:100',
        ]);

        $this->repository->create($request->only(['nidn', 'nama']));

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen berhasil ditambahkan');
    }

    public function show(string $nidn)
    {
        $dosen = $this->repository->getWithJadwal($nidn);

        if (!$dosen) {
            return redirect()->route('admin.dosen.index')
                ->with('error', 'Dosen tidak ditemukan');
        }

        $totalMahasiswa = $this->repository->getTotalMahasiswa($nidn);

        return view('admin.dosen.show', compact('dosen', 'totalMahasiswa'));
    }

    public function edit(string $nidn)
    {
        $dosen = $this->repository->findByNidn($nidn);

        if (!$dosen) {
            return redirect()->route('admin.dosen.index')
                ->with('error', 'Dosen tidak ditemukan');
        }

        return view('admin.dosen.edit', compact('dosen'));
    }

    public function update(Request $request, string $nidn)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        $this->repository->update($nidn, $request->only(['nama']));

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen berhasil diperbarui');
    }

    public function destroy(string $nidn)
    {
        $this->repository->delete($nidn);

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen berhasil dihapus');
    }
}
