<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MataKuliahRepositoryInterface;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    protected MataKuliahRepositoryInterface $repository;

    public function __construct(MataKuliahRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            $mataKuliah = $this->repository->search($search);
        } else {
            $mataKuliah = $this->repository->getAll();
        }

        return view('admin.mata_kuliah.index', compact('mataKuliah', 'search'));
    }

    public function create()
    {
        return view('admin.mata_kuliah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mata_kuliah' => 'required|string|max:10|unique:mata_kuliah,kode_mata_kuliah',
            'nama_mata_kuliah' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
        ]);

        $this->repository->create($request->only(['kode_mata_kuliah', 'nama_mata_kuliah', 'sks']));

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata Kuliah berhasil ditambahkan');
    }

    public function show(int $id)
    {
        $mataKuliah = $this->repository->findById($id);

        if (!$mataKuliah) {
            return redirect()->route('admin.mata-kuliah.index')
                ->with('error', 'Mata Kuliah tidak ditemukan');
        }

        return view('admin.mata_kuliah.show', compact('mataKuliah'));
    }

    public function edit(int $id)
    {
        $mataKuliah = $this->repository->findById($id);

        if (!$mataKuliah) {
            return redirect()->route('admin.mata-kuliah.index')
                ->with('error', 'Mata Kuliah tidak ditemukan');
        }

        return view('admin.mata_kuliah.edit', compact('mataKuliah'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'kode_mata_kuliah' => 'required|string|max:10|unique:mata_kuliah,kode_mata_kuliah,' . $id,
            'nama_mata_kuliah' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
        ]);

        $this->repository->update($id, $request->only(['kode_mata_kuliah', 'nama_mata_kuliah', 'sks']));

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata Kuliah berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata Kuliah berhasil dihapus');
    }
}
