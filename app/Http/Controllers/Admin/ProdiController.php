<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            $prodi = Prodi::with(['jurusan.fakultas', 'kaprodi'])
                ->where('nama_prodi', 'like', "%{$search}%")
                ->get();
        } else {
            $prodi = Prodi::with(['jurusan.fakultas', 'kaprodi'])->get();
        }

        return view('admin.prodi.index', compact('prodi', 'search'));
    }

    public function create()
    {
        $jurusanList = Jurusan::with('fakultas')->get();
        $kaprodiList = User::where('role', 'kaprodi')->get();
        return view('admin.prodi.create', compact('jurusanList', 'kaprodiList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusan,id',
            'nama_prodi' => 'required|string|max:100',
            'kaprodi_user_id' => 'nullable|exists:users,id',
        ]);

        Prodi::create($request->only(['jurusan_id', 'nama_prodi', 'kaprodi_user_id']));

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Prodi berhasil ditambahkan');
    }

    public function show(int $id)
    {
        $prodi = Prodi::with(['jurusan.fakultas', 'kaprodi'])->findOrFail($id);
        return view('admin.prodi.show', compact('prodi'));
    }

    public function edit(int $id)
    {
        $prodi = Prodi::findOrFail($id);
        $jurusanList = Jurusan::with('fakultas')->get();
        $kaprodiList = User::where('role', 'kaprodi')->get();
        return view('admin.prodi.edit', compact('prodi', 'jurusanList', 'kaprodiList'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusan,id',
            'nama_prodi' => 'required|string|max:100',
            'kaprodi_user_id' => 'nullable|exists:users,id',
        ]);

        Prodi::findOrFail($id)->update($request->only(['jurusan_id', 'nama_prodi', 'kaprodi_user_id']));

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Prodi berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        Prodi::findOrFail($id)->delete();

        return redirect()->route('admin.prodi.index')
            ->with('success', 'Prodi berhasil dihapus');
    }
}
