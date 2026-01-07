<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\{Jabatan, Mahasiswa, User, UangKas, Pengeluaran, SaldoKas};
use Illuminate\Support\Facades\DB;

#[Layout('components.app-admin')]
class Dashboard extends Component
{
    public function render()
    {
        $totalJabatan = Jabatan::count();
        $totalUser = User::count();
        $totalMahasiswa = Mahasiswa::count();
        $mahasiswaAktif = Mahasiswa::where('status', 'aktif')->count();

        $saldoKas = SaldoKas::first()->saldo ?? 0;
        $totalPemasukan = UangKas::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');

        // Data bulan ini
        $bulanIni = now()->format('F');
        $tahunIni = now()->year;
        $pemasukanBulanIni = UangKas::where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->sum('jumlah');
        $pengeluaranBulanIni = Pengeluaran::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', $tahunIni)
            ->sum('jumlah');

        // Transaksi Terakhir
        $transaksiTerakhir = UangKas::with('mahasiswa', 'user')
            ->latest()
            ->take(5)
            ->get();

        $pengeluaranTerakhir = Pengeluaran::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.dashboard', [
            'totalJabatan' => $totalJabatan,
            'totalUser' => $totalUser,
            'totalMahasiswa' => $totalMahasiswa,
            'mahasiswaAktif' => $mahasiswaAktif,
            'saldoKas' => $saldoKas,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'pemasukanBulanIni' => $pemasukanBulanIni,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
            'transaksiTerakhir' => $transaksiTerakhir,
            'pengeluaranTerakhir' => $pengeluaranTerakhir,
        ]);
    }
}
