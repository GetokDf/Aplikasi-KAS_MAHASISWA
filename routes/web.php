<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Jabatan\Index as JabatanIndex;
use App\Livewire\Mahasiswa\Index as MahasiswaIndex;
use App\Livewire\UangKas\Index as UangKasIndex;
use App\Livewire\Pengeluaran\Index as PengeluaranIndex;
use App\Livewire\Laporan\Index as LaporanIndex;

// Redirect ke dashboard kalau sudah login, kalau belum ke login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/jabatan', JabatanIndex::class)->name('jabatan.index');
    Route::get('/mahasiswa', MahasiswaIndex::class)->name('mahasiswa.index');
    Route::get('/uang-kas', UangKasIndex::class)->name('uangkas.index');
    Route::get('/pengeluaran', PengeluaranIndex::class)->name('pengeluaran.index');
    Route::get('/laporan', LaporanIndex::class)->name('laporan.index');
});

require __DIR__ . '/auth.php';
