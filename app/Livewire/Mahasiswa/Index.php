<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Mahasiswa;
use App\Models\Jabatan;

#[Layout('components.app-admin')]
class Index extends Component
{
    use WithPagination;

    public $nim, $nama, $jabatan_id, $email, $no_hp, $status = 'aktif', $mahasiswa_id;
    public $isEdit = false;
    public $showModal = false;
    public $search = '';
    public $filterStatus = '';

    protected $rules = [
        'nim' => 'required|unique:mahasiswa,nim',
        'nama' => 'required|min:3',
        'jabatan_id' => 'nullable|exists:jabatan,id',
        'email' => 'required|email|unique:mahasiswa,email',
        'no_hp' => 'nullable',
        'status' => 'required|in:aktif,tidak_aktif',
    ];

    protected $messages = [
        'nim.required' => 'NIM wajib diisi',
        'nim.unique' => 'NIM sudah terdaftar',
        'nama.required' => 'Nama wajib diisi',
        'nama.min' => 'Nama minimal 3 karakter',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah terdaftar',
        'status.required' => 'Status wajib dipilih',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
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
        $this->nim = '';
        $this->nama = '';
        $this->jabatan_id = '';
        $this->email = '';
        $this->no_hp = '';
        $this->status = 'aktif';
        $this->mahasiswa_id = null;
        $this->isEdit = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        if ($this->isEdit) {
            $this->rules['nim'] = 'required|unique:mahasiswa,nim,' . $this->mahasiswa_id;
            $this->rules['email'] = 'required|email|unique:mahasiswa,email,' . $this->mahasiswa_id;
        }

        $this->validate();

        if ($this->isEdit) {
            $mahasiswa = Mahasiswa::find($this->mahasiswa_id);
            $mahasiswa->update([
                'nim' => $this->nim,
                'nama' => $this->nama,
                'jabatan_id' => $this->jabatan_id ?: null,
                'email' => $this->email,
                'no_hp' => $this->no_hp,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Mahasiswa berhasil diupdate!');
        } else {
            Mahasiswa::create([
                'nim' => $this->nim,
                'nama' => $this->nama,
                'jabatan_id' => $this->jabatan_id ?: null,
                'email' => $this->email,
                'no_hp' => $this->no_hp,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Mahasiswa berhasil ditambahkan!');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $mahasiswa = Mahasiswa::find($id);
        $this->mahasiswa_id = $id;
        $this->nim = $mahasiswa->nim;
        $this->nama = $mahasiswa->nama;
        $this->jabatan_id = $mahasiswa->jabatan_id;
        $this->email = $mahasiswa->email;
        $this->no_hp = $mahasiswa->no_hp;
        $this->status = $mahasiswa->status;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        $mahasiswa = Mahasiswa::find($id);

        // Cek apakah mahasiswa memiliki transaksi
        if ($mahasiswa->uangKas()->count() > 0) {
            session()->flash('error', 'Mahasiswa tidak dapat dihapus karena sudah memiliki transaksi kas!');
            return;
        }

        $mahasiswa->delete();
        session()->flash('message', 'Mahasiswa berhasil dihapus!');
    }

    public function render()
    {
        $query = Mahasiswa::with('jabatan')
            ->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('nim', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $mahasiswas = $query->orderBy('created_at', 'desc')->paginate(10);
        $jabatans = Jabatan::all();

        return view('livewire.mahasiswa.index', [
            'mahasiswas' => $mahasiswas,
            'jabatans' => $jabatans,
        ]);
    }
}
