<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\MataKuliahController as AdminMataKuliahController;
use App\Http\Controllers\Admin\JadwalController as AdminJadwalController;
use App\Http\Controllers\Admin\RuanganController as AdminRuanganController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\FakultasController as AdminFakultasController;
use App\Http\Controllers\Admin\JurusanController as AdminJurusanController;
use App\Http\Controllers\Admin\ProdiController as AdminProdiController;
use App\Http\Controllers\Kaprodi\KaprodiController;
use App\Http\Controllers\Pimpinan\PimpinanController;
use App\Http\Controllers\Dosen\DosenController;
use App\Http\Controllers\Mahasiswa\MahasiswaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home redirect
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'kaprodi' => redirect()->route('kaprodi.dashboard'),
            'pimpinan' => redirect()->route('pimpinan.dashboard'),
            'dosen' => redirect()->route('dosen.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Mahasiswa CRUD
    Route::resource('mahasiswa', AdminMahasiswaController::class);
    
    // Dosen CRUD
    Route::resource('dosen', AdminDosenController::class);
    
    // Mata Kuliah CRUD
    Route::resource('mata-kuliah', AdminMataKuliahController::class);
    
    // Jadwal CRUD
    Route::resource('jadwal', AdminJadwalController::class);
    Route::post('jadwal/check-conflict', [AdminJadwalController::class, 'checkConflict'])->name('jadwal.check-conflict');
    
    // Ruangan CRUD
    Route::resource('ruangan', AdminRuanganController::class);
    
    // Users CRUD
    Route::resource('users', AdminUserController::class);
    
    // Fakultas CRUD
    Route::resource('fakultas', AdminFakultasController::class);
    
    // Jurusan CRUD
    Route::resource('jurusan', AdminJurusanController::class);
    
    // Prodi CRUD
    Route::resource('prodi', AdminProdiController::class);
});

// Kaprodi Routes (EPBM Management)
Route::prefix('kaprodi')->middleware('kaprodi')->name('kaprodi.')->group(function () {
    Route::get('/dashboard', [KaprodiController::class, 'dashboard'])->name('dashboard');
    
    // EPBM Periode
    Route::get('/epbm', [KaprodiController::class, 'epbmIndex'])->name('epbm.index');
    Route::get('/epbm/create', [KaprodiController::class, 'epbmCreate'])->name('epbm.create');
    Route::post('/epbm', [KaprodiController::class, 'epbmStore'])->name('epbm.store');
    Route::get('/epbm/{id}', [KaprodiController::class, 'epbmShow'])->name('epbm.show');
    Route::get('/epbm/{id}/edit', [KaprodiController::class, 'epbmEdit'])->name('epbm.edit');
    Route::put('/epbm/{id}', [KaprodiController::class, 'epbmUpdate'])->name('epbm.update');
    Route::delete('/epbm/{id}', [KaprodiController::class, 'epbmDestroy'])->name('epbm.destroy');
    Route::post('/epbm/{id}/toggle-active', [KaprodiController::class, 'epbmToggleActive'])->name('epbm.toggle-active');
    
    // EPBM Pertanyaan
    Route::post('/epbm/{id}/pertanyaan', [KaprodiController::class, 'pertanyaanStore'])->name('pertanyaan.store');
    Route::put('/pertanyaan/{id}', [KaprodiController::class, 'pertanyaanUpdate'])->name('pertanyaan.update');
    Route::delete('/pertanyaan/{id}', [KaprodiController::class, 'pertanyaanDestroy'])->name('pertanyaan.destroy');
    
    // Summary & Jawaban
    Route::get('/summary', [KaprodiController::class, 'summary'])->name('summary');
    Route::get('/jawaban/{periodeId}', [KaprodiController::class, 'jawabanIndex'])->name('jawaban.index');
    Route::get('/jawaban/{periodeId}/detail/{nim}', [KaprodiController::class, 'jawabanDetail'])->name('jawaban.detail');
});

// Pimpinan Routes (Summary View)
Route::prefix('pimpinan')->middleware('pimpinan')->name('pimpinan.')->group(function () {
    Route::get('/dashboard', [PimpinanController::class, 'dashboard'])->name('dashboard');
    Route::get('/summary', [PimpinanController::class, 'summary'])->name('summary');
    Route::get('/summary/periode/{id}', [PimpinanController::class, 'summaryPeriode'])->name('summary.periode');
    Route::get('/summary/pertanyaan/{periodeId}', [PimpinanController::class, 'summaryPertanyaan'])->name('summary.pertanyaan');
});

// Dosen Routes
Route::prefix('dosen')->middleware('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal', [DosenController::class, 'jadwal'])->name('jadwal');
    Route::get('/nilai', [DosenController::class, 'nilai'])->name('nilai');
    Route::get('/nilai/mahasiswa/{jadwalId}', [DosenController::class, 'getMahasiswaByJadwal'])->name('nilai.mahasiswa');
    Route::post('/nilai/save', [DosenController::class, 'saveNilai'])->name('nilai.save');
    
    // Absensi
    Route::get('/absensi', [DosenController::class, 'absensiIndex'])->name('absensi.index');
    Route::get('/absensi/{jadwalId}', [DosenController::class, 'absensiJadwal'])->name('absensi.jadwal');
    Route::post('/absensi/save', [DosenController::class, 'absensiSave'])->name('absensi.save');
});

// Mahasiswa Routes
Route::prefix('mahasiswa')->middleware('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
    Route::get('/krs', [MahasiswaController::class, 'krs'])->name('krs');
    Route::post('/krs/add', [MahasiswaController::class, 'addKrs'])->name('krs.add');
    Route::post('/krs/remove', [MahasiswaController::class, 'removeKrs'])->name('krs.remove');
    Route::post('/krs/submit', [MahasiswaController::class, 'submitKrs'])->name('krs.submit');
    Route::get('/krs/print', [MahasiswaController::class, 'printKrs'])->name('krs.print');
    Route::get('/jadwal', [MahasiswaController::class, 'jadwal'])->name('jadwal');
    Route::get('/nilai', [MahasiswaController::class, 'nilai'])->name('nilai');
    Route::get('/hasil-studi', [MahasiswaController::class, 'hasilStudi'])->name('hasil-studi');
    
    // EPBM
    Route::get('/epbm', [MahasiswaController::class, 'epbmIndex'])->name('epbm.index');
    Route::get('/epbm/{rencanaStudiId}', [MahasiswaController::class, 'epbmForm'])->name('epbm.form');
    Route::post('/epbm/{rencanaStudiId}', [MahasiswaController::class, 'epbmSubmit'])->name('epbm.submit');
    
    // Absensi
    Route::get('/absensi', [MahasiswaController::class, 'absensiIndex'])->name('absensi.index');
});
