<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\RuanganRepositoryInterface;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    protected RuanganRepositoryInterface $repository;

    public function __construct(RuanganRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            $ruangan = $this->repository->search($search);
        } else {
            $ruangan = $this->repository->getAll();
        }

        return view('admin.ruangan.index', compact('ruangan', 'search'));
    }

    public function create()
    {
        return view('admin.ruangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:100',
        ]);

        $this->repository->create($request->only(['nama_ruangan']));

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function show(int $id)
    {
        $ruangan = $this->repository->findById($id);

        if (!$ruangan) {
            return redirect()->route('admin.ruangan.index')
                ->with('error', 'Ruangan tidak ditemukan');
        }

        $ruangan->load('jadwal.mataKuliah');

        return view('admin.ruangan.show', compact('ruangan'));
    }

    public function edit(int $id)
    {
        $ruangan = $this->repository->findById($id);

        if (!$ruangan) {
            return redirect()->route('admin.ruangan.index')
                ->with('error', 'Ruangan tidak ditemukan');
        }

        return view('admin.ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:100',
        ]);

        $this->repository->update($id, $request->only(['nama_ruangan']));

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil dihapus');
    }
}
