<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        if ($search) {
            $fakultas = Fakultas::where('nama_fakultas', 'like', "%{$search}%")->get();
        } else {
            $fakultas = Fakultas::all();
        }

        return view('admin.fakultas.index', compact('fakultas', 'search'));
    }

    public function create()
    {
        return view('admin.fakultas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_fakultas' => 'required|string|max:100',
        ]);

        Fakultas::create($request->only(['nama_fakultas']));

        return redirect()->route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil ditambahkan');
    }

    public function show(int $id)
    {
        $fakultas = Fakultas::with('jurusan')->findOrFail($id);
        return view('admin.fakultas.show', compact('fakultas'));
    }

    public function edit(int $id)
    {
        $fakultas = Fakultas::findOrFail($id);
        return view('admin.fakultas.edit', compact('fakultas'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_fakultas' => 'required|string|max:100',
        ]);

        Fakultas::findOrFail($id)->update($request->only(['nama_fakultas']));

        return redirect()->route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        Fakultas::findOrFail($id)->delete();

        return redirect()->route('admin.fakultas.index')
            ->with('success', 'Fakultas berhasil dihapus');
    }
}
