<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Show login form
     */
    public function index()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard(Auth::user()->role);
        }

        return view('auth.login');
    }

    /**
     * Process login
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'password' => 'required',
        ]);

        $user = $this->userRepository->findByKode($request->kode);

        if (!$user) {
            return back()->withInput()->with('error', 'Username tidak ditemukan');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withInput()->with('error', 'Password salah');
        }

        Auth::login($user, $request->filled('remember'));

        $request->session()->regenerate();

        return $this->redirectToDashboard($user->role);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout');
    }

    /**
     * Redirect to dashboard based on role
     */
    private function redirectToDashboard(string $role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'kaprodi' => redirect()->route('kaprodi.dashboard'),
            'pimpinan' => redirect()->route('pimpinan.dashboard'),
            'dosen' => redirect()->route('dosen.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
