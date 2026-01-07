<?php

namespace App\Livewire\UangKas;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\{UangKas, Mahasiswa, SaldoKas};
use Illuminate\Support\Facades\DB;

#[Layout('components.app-admin')]
class Index extends Component
{
    use WithPagination;

    public $mahasiswa_id, $jumlah, $tanggal_bayar, $bulan, $tahun, $keterangan, $uangkas_id;
    public $isEdit = false;
    public $showModal = false;
    public $search = '';
    public $filterBulan = '';
    public $filterTahun = '';

    protected $rules = [
        'mahasiswa_id' => 'required|exists:mahasiswa,id',
        'jumlah' => 'required|numeric|min:1000',
        'tanggal_bayar' => 'required|date',
        'bulan' => 'required',
        'tahun' => 'required|numeric',
        'keterangan' => 'nullable',
    ];

    protected $messages = [
        'mahasiswa_id.required' => 'Mahasiswa wajib dipilih',
        'jumlah.required' => 'Jumlah wajib diisi',
        'jumlah.numeric' => 'Jumlah harus berupa angka',
        'jumlah.min' => 'Jumlah minimal Rp 1.000',
        'tanggal_bayar.required' => 'Tanggal bayar wajib diisi',
        'bulan.required' => 'Bulan wajib dipilih',
        'tahun.required' => 'Tahun wajib diisi',
    ];

    public function mount()
    {
        $this->tanggal_bayar = now()->format('Y-m-d');
        $this->bulan = now()->format('F');
        $this->tahun = now()->year;
        $this->filterTahun = now()->year;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterBulan()
    {
        $this->resetPage();
    }

    public function updatingFilterTahun()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->mahasiswa_id = '';
        $this->jumlah = '';
        $this->tanggal_bayar = now()->format('Y-m-d');
        $this->bulan = now()->format('F');
        $this->tahun = now()->year;
        $this->keterangan = '';
        $this->uangkas_id = null;
        $this->isEdit = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            if ($this->isEdit) {
                $uangKas = UangKas::find($this->uangkas_id);
                $selisih = $this->jumlah - $uangKas->jumlah;

                $uangKas->update([
                    'mahasiswa_id' => $this->mahasiswa_id,
                    'jumlah' => $this->jumlah,
                    'tanggal_bayar' => $this->tanggal_bayar,
                    'bulan' => $this->bulan,
                    'tahun' => $this->tahun,
                    'keterangan' => $this->keterangan,
                    'user_id' => auth()->id(),
                ]);

                // Update saldo
                $saldo = SaldoKas::first();
                $saldo->saldo += $selisih;
                $saldo->save();

                session()->flash('message', 'Pembayaran kas berhasil diupdate!');
            } else {
                UangKas::create([
                    'mahasiswa_id' => $this->mahasiswa_id,
                    'jumlah' => $this->jumlah,
                    'tanggal_bayar' => $this->tanggal_bayar,
                    'bulan' => $this->bulan,
                    'tahun' => $this->tahun,
                    'keterangan' => $this->keterangan,
                    'user_id' => auth()->id(),
                ]);

                // Update saldo
                $saldo = SaldoKas::first();
                if (!$saldo) {
                    $saldo = SaldoKas::create(['saldo' => 0]);
                }
                $saldo->saldo += $this->jumlah;
                $saldo->save();

                session()->flash('message', 'Pembayaran kas berhasil ditambahkan!');
            }

            DB::commit();
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $uangKas = UangKas::find($id);
        $this->uangkas_id = $id;
        $this->mahasiswa_id = $uangKas->mahasiswa_id;
        $this->jumlah = $uangKas->jumlah;
        $this->tanggal_bayar = $uangKas->tanggal_bayar;
        $this->bulan = $uangKas->bulan;
        $this->tahun = $uangKas->tahun;
        $this->keterangan = $uangKas->keterangan;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $uangKas = UangKas::find($id);

            // Update saldo (kurangi)
            $saldo = SaldoKas::first();
            $saldo->saldo -= $uangKas->jumlah;
            $saldo->save();

            $uangKas->delete();

            DB::commit();
            session()->flash('message', 'Pembayaran kas berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = UangKas::with(['mahasiswa', 'user']);

        if ($this->search) {
            $query->whereHas('mahasiswa', function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('nim', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterBulan) {
            $query->where('bulan', $this->filterBulan);
        }

        if ($this->filterTahun) {
            $query->where('tahun', $this->filterTahun);
        }

        $uangKas = $query->orderBy('tanggal_bayar', 'desc')->paginate(10);
        $mahasiswas = Mahasiswa::where('status', 'aktif')->orderBy('nama')->get();
        $saldoKas = SaldoKas::first()->saldo ?? 0;

        // Statistik
        $totalPemasukan = UangKas::sum('jumlah');
        $pemasukanBulanIni = UangKas::where('bulan', now()->format('F'))
            ->where('tahun', now()->year)
            ->sum('jumlah');

        $bulanList = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        return view('livewire.uang-kas.index', [
            'uangKas' => $uangKas,
            'mahasiswas' => $mahasiswas,
            'saldoKas' => $saldoKas,
            'totalPemasukan' => $totalPemasukan,
            'pemasukanBulanIni' => $pemasukanBulanIni,
            'bulanList' => $bulanList,
        ]);
    }
}
