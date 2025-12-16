<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $users = $this->repository->getAll();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_user' => 'required|string|max:100',
            'kode' => 'required|string|max:20|unique:users,kode',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,dosen,mahasiswa',
        ]);

        $this->repository->createWithPassword([
            'nama_user' => $request->nama_user,
            'kode' => $request->kode,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function show(int $id)
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User tidak ditemukan');
        }

        return view('admin.users.show', compact('user'));
    }

    public function edit(int $id)
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User tidak ditemukan');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'nama_user' => 'required|string|max:100',
            'kode' => 'required|string|max:20|unique:users,kode,' . $id,
            'role' => 'required|in:admin,dosen,mahasiswa',
        ]);

        $data = $request->only(['nama_user', 'kode', 'role']);

        // Update password only if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $this->repository->updatePassword($id, $request->password);
        }

        $this->repository->update($id, $data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
