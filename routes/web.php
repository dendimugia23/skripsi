<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminPetaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PengaduanuserController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperAdminPetaController;
use App\Http\Controllers\SuperAdminRekapitulasiController;
use App\Http\Controllers\SuperAdminPengaduanController;
use App\Http\Controllers\UserController;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WifiExport;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Mengatur semua route dalam aplikasi.
*/

// Halaman utama
Route::get('/', [HomeController::class, 'index'])->name('index');

// Menu Peta untuk User
Route::get('/user/map', [UserController::class, 'userMap'])->name('user.map');

// Pengaduan untuk User
Route::get('/pengaduan', [PengaduanuserController::class, 'index'])->name('pengaduan.user');
Route::post('/pengaduan', [PengaduanuserController::class, 'store'])->name('pengaduan.store');
Route::post('/search-pengaduan', [HomeController::class, 'searchPengaduan'])->name('search.pengaduan');

// Autentikasi
Auth::routes();

// Route yang hanya bisa diakses oleh pengguna yang sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'home'])->name('home');

    // Route untuk Admin
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Pengaduan untuk Admin
        Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('admin.pengaduan');
        Route::put('/pengaduan/{pengaduan}/validasi', [PengaduanController::class, 'validasi'])->name('admin.validasi');
        Route::put('/pengaduan/{pengaduan}/tolak', [PengaduanController::class, 'tolak'])->name('admin.tolak');

        // CRUD WiFi & Peta
        Route::prefix('peta')->group(function () {
            Route::get('/', [AdminPetaController::class, 'index'])->name('admin.peta');
            Route::get('/create', [AdminPetaController::class, 'create'])->name('admin.create');
            Route::post('/', [AdminPetaController::class, 'store'])->name('admin.store');
            Route::get('/{wifi}/edit', [AdminPetaController::class, 'edit'])->name('admin.edit');
            Route::put('/{wifi}', [AdminPetaController::class, 'update'])->name('admin.update');
            Route::delete('/{wifi}', [AdminPetaController::class, 'destroy'])->name('admin.destroy');

            // Menampilkan peta
            Route::get('/map', [AdminPetaController::class, 'map'])->name('admin.map');

            // Export WiFi ke Excel
            Route::get('/wifi/export', [AdminPetaController::class, 'export'])->name('admin.wifi.export');
        });
    });

    // Route untuk Super Admin
    Route::middleware(['role:super_admin'])->prefix('superadmin')->group(function () {
        Route::get('/', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');

        // Rekapitulasi Data
        Route::get('/rekapitulasi', [SuperAdminRekapitulasiController::class, 'index'])->name('superadmin.rekapitulasi');
        Route::get('/pengaduan', [SuperAdminPengaduanController::class, 'index'])->name('superadmin.pengaduan');

        // Peta WiFi
        Route::get('/peta', [SuperAdminPetaController::class, 'index'])->name('superadmin.peta');
        Route::get('/map', [SuperAdminPetaController::class, 'map'])->name('superadmin.map');

        // Validasi WiFi
        Route::put('/validasi/{wifi}', [SuperAdminPetaController::class, 'validasi'])->name('superadmin.validasi');
        Route::get('/rekapitulasi/export-excel', [SuperAdminRekapitulasiController::class, 'exportExcel'])->name('superadmin.rekapitulasi.excel');
        Route::get('/rekapitulasi/export-pdf', [SuperAdminRekapitulasiController::class, 'exportPDF'])->name('superadmin.rekapitulasi.pdf');

    });
});
