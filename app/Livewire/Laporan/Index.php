<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\{UangKas, Pengeluaran, SaldoKas, Mahasiswa};
use Illuminate\Support\Facades\DB;

#[Layout('components.app-admin')]
class Index extends Component
{
    public $bulan = '';
    public $tahun = '';
    public $jenis = 'semua'; // semua, pemasukan, pengeluaran

    public function mount()
    {
        $this->bulan = now()->month;
        $this->tahun = now()->year;
    }

    public function render()
    {
        $query = null;
        $pemasukan = collect();
        $pengeluaran = collect();

        // Data Pemasukan
        if ($this->jenis == 'semua' || $this->jenis == 'pemasukan') {
            $pemasukanQuery = UangKas::with(['mahasiswa', 'user'])
                ->whereYear('tanggal_bayar', $this->tahun);

            if ($this->bulan) {
                $pemasukanQuery->whereMonth('tanggal_bayar', $this->bulan);
            }

            $pemasukan = $pemasukanQuery->orderBy('tanggal_bayar', 'desc')->get();
        }

        // Data Pengeluaran
        if ($this->jenis == 'semua' || $this->jenis == 'pengeluaran') {
            $pengeluaranQuery = Pengeluaran::with('user')
                ->whereYear('tanggal', $this->tahun);

            if ($this->bulan) {
                $pengeluaranQuery->whereMonth('tanggal', $this->bulan);
            }

            $pengeluaran = $pengeluaranQuery->orderBy('tanggal', 'desc')->get();
        }

        // Statistik
        $totalPemasukan = $pemasukan->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $selisih = $totalPemasukan - $totalPengeluaran;
        $saldoKas = SaldoKas::first()->saldo ?? 0;

        // Mahasiswa terbanyak bayar
        $topMahasiswa = UangKas::select('mahasiswa_id', DB::raw('COUNT(*) as total_bayar'))
            ->whereYear('tanggal_bayar', $this->tahun)
            ->groupBy('mahasiswa_id')
            ->orderBy('total_bayar', 'desc')
            ->limit(5)
            ->with('mahasiswa')
            ->get();

        // Pengeluaran terbesar
        $topPengeluaran = Pengeluaran::whereYear('tanggal', $this->tahun)
            ->orderBy('jumlah', 'desc')
            ->limit(5)
            ->get();

        // Chart data - Pemasukan vs Pengeluaran per bulan
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = [
                'bulan' => date('M', mktime(0, 0, 0, $i, 1)),
                'pemasukan' => UangKas::whereMonth('tanggal_bayar', $i)
                    ->whereYear('tanggal_bayar', $this->tahun)
                    ->sum('jumlah'),
                'pengeluaran' => Pengeluaran::whereMonth('tanggal', $i)
                    ->whereYear('tanggal', $this->tahun)
                    ->sum('jumlah'),
            ];
        }

        return view('livewire.laporan.index', [
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'selisih' => $selisih,
            'saldoKas' => $saldoKas,
            'topMahasiswa' => $topMahasiswa,
            'topPengeluaran' => $topPengeluaran,
            'chartData' => $chartData,
        ]);
    }
}
