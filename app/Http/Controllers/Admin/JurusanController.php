<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            $jurusan = Jurusan::with('fakultas')
                ->where('nama_jurusan', 'like', "%{$search}%")
                ->get();
        } else {
            $jurusan = Jurusan::with('fakultas')->get();
        }

        return view('admin.jurusan.index', compact('jurusan', 'search'));
    }

    public function create()
    {
        $fakultasList = Fakultas::all();
        return view('admin.jurusan.create', compact('fakultasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'nama_jurusan' => 'required|string|max:100',
        ]);

        Jurusan::create($request->only(['fakultas_id', 'nama_jurusan']));

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan');
    }

    public function show(int $id)
    {
        $jurusan = Jurusan::with(['fakultas', 'prodi'])->findOrFail($id);
        return view('admin.jurusan.show', compact('jurusan'));
    }

    public function edit(int $id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $fakultasList = Fakultas::all();
        return view('admin.jurusan.edit', compact('jurusan', 'fakultasList'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'nama_jurusan' => 'required|string|max:100',
        ]);

        Jurusan::findOrFail($id)->update($request->only(['fakultas_id', 'nama_jurusan']));

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        Jurusan::findOrFail($id)->delete();

        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus');
    }
}
