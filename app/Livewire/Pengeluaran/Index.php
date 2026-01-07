<?php

namespace App\Livewire\Pengeluaran;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\{Pengeluaran, SaldoKas};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

#[Layout('components.app-admin')]
class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $nama_pengeluaran, $jumlah, $tanggal, $keterangan, $bukti, $pengeluaran_id;
    public $oldBukti;
    public $isEdit = false;
    public $showModal = false;
    public $search = '';
    public $filterBulan = '';
    public $filterTahun = '';

    protected $rules = [
        'nama_pengeluaran' => 'required|min:3',
        'jumlah' => 'required|numeric|min:1000',
        'tanggal' => 'required|date',
        'keterangan' => 'nullable',
        'bukti' => 'nullable|image|max:2048',
    ];

    protected $messages = [
        'nama_pengeluaran.required' => 'Nama pengeluaran wajib diisi',
        'nama_pengeluaran.min' => 'Nama pengeluaran minimal 3 karakter',
        'jumlah.required' => 'Jumlah wajib diisi',
        'jumlah.numeric' => 'Jumlah harus berupa angka',
        'jumlah.min' => 'Jumlah minimal Rp 1.000',
        'tanggal.required' => 'Tanggal wajib diisi',
        'bukti.image' => 'Bukti harus berupa gambar',
        'bukti.max' => 'Ukuran bukti maksimal 2MB',
    ];

    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d');
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
        $this->nama_pengeluaran = '';
        $this->jumlah = '';
        $this->tanggal = now()->format('Y-m-d');
        $this->keterangan = '';
        $this->bukti = null;
        $this->oldBukti = null;
        $this->pengeluaran_id = null;
        $this->isEdit = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        if ($this->isEdit) {
            $this->rules['bukti'] = 'nullable|image|max:2048';
        }

        $this->validate();

        // Cek saldo mencukupi
        $saldo = SaldoKas::first();
        if (!$this->isEdit && $saldo->saldo < $this->jumlah) {
            session()->flash('error', 'Saldo kas tidak mencukupi!');
            return;
        }

        DB::beginTransaction();
        try {
            $data = [
                'nama_pengeluaran' => $this->nama_pengeluaran,
                'jumlah' => $this->jumlah,
                'tanggal' => $this->tanggal,
                'keterangan' => $this->keterangan,
                'user_id' => auth()->id(),
            ];

            // Handle upload bukti
            if ($this->bukti) {
                $filename = time() . '_' . $this->bukti->getClientOriginalName();
                $this->bukti->storeAs('public/bukti_pengeluaran', $filename);
                $data['bukti'] = $filename;

                // Hapus bukti lama jika edit
                if ($this->isEdit && $this->oldBukti) {
                    Storage::delete('public/bukti_pengeluaran/' . $this->oldBukti);
                }
            }

            if ($this->isEdit) {
                $pengeluaran = Pengeluaran::find($this->pengeluaran_id);
                $selisih = $this->jumlah - $pengeluaran->jumlah;

                $pengeluaran->update($data);

                // Update saldo
                $saldo->saldo -= $selisih;
                $saldo->save();

                session()->flash('message', 'Pengeluaran berhasil diupdate!');
            } else {
                Pengeluaran::create($data);

                // Update saldo (kurangi)
                if (!$saldo) {
                    $saldo = SaldoKas::create(['saldo' => 0]);
                }
                $saldo->saldo -= $this->jumlah;
                $saldo->save();

                session()->flash('message', 'Pengeluaran berhasil ditambahkan!');
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
        $pengeluaran = Pengeluaran::find($id);
        $this->pengeluaran_id = $id;
        $this->nama_pengeluaran = $pengeluaran->nama_pengeluaran;
        $this->jumlah = $pengeluaran->jumlah;
        $this->tanggal = $pengeluaran->tanggal;
        $this->keterangan = $pengeluaran->keterangan;
        $this->oldBukti = $pengeluaran->bukti;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $pengeluaran = Pengeluaran::find($id);

            // Hapus file bukti
            if ($pengeluaran->bukti) {
                Storage::delete('public/bukti_pengeluaran/' . $pengeluaran->bukti);
            }

            // Update saldo (tambah kembali)
            $saldo = SaldoKas::first();
            $saldo->saldo += $pengeluaran->jumlah;
            $saldo->save();

            $pengeluaran->delete();

            DB::commit();
            session()->flash('message', 'Pengeluaran berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = Pengeluaran::with('user');

        if ($this->search) {
            $query->where('nama_pengeluaran', 'like', '%' . $this->search . '%');
        }

        if ($this->filterBulan) {
            $query->whereMonth('tanggal', $this->filterBulan);
        }

        if ($this->filterTahun) {
            $query->whereYear('tanggal', $this->filterTahun);
        }

        $pengeluarans = $query->orderBy('tanggal', 'desc')->paginate(10);
        $saldoKas = SaldoKas::first()->saldo ?? 0;

        // Statistik
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $pengeluaranBulanIni = Pengeluaran::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('jumlah');

        return view('livewire.pengeluaran.index', [
            'pengeluarans' => $pengeluarans,
            'saldoKas' => $saldoKas,
            'totalPengeluaran' => $totalPengeluaran,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
        ]);
    }
}
