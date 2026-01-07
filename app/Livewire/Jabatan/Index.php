<?php

namespace App\Livewire\Jabatan;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\Jabatan;

#[Layout('components.app-admin')]
class Index extends Component
{
    use WithPagination;

    public $nama_jabatan, $deskripsi, $jabatan_id;
    public $isEdit = false;
    public $showModal = false;
    public $search = '';

    protected $rules = [
        'nama_jabatan' => 'required|min:3',
        'deskripsi' => 'nullable',
    ];

    protected $messages = [
        'nama_jabatan.required' => 'Nama jabatan wajib diisi',
        'nama_jabatan.min' => 'Nama jabatan minimal 3 karakter',
    ];

    public function updatingSearch()
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
        $this->nama_jabatan = '';
        $this->deskripsi = '';
        $this->jabatan_id = null;
        $this->isEdit = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $jabatan = Jabatan::find($this->jabatan_id);
            $jabatan->update([
                'nama_jabatan' => $this->nama_jabatan,
                'deskripsi' => $this->deskripsi,
            ]);
            session()->flash('message', 'Jabatan berhasil diupdate!');
        } else {
            Jabatan::create([
                'nama_jabatan' => $this->nama_jabatan,
                'deskripsi' => $this->deskripsi,
            ]);
            session()->flash('message', 'Jabatan berhasil ditambahkan!');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $jabatan = Jabatan::find($id);
        $this->jabatan_id = $id;
        $this->nama_jabatan = $jabatan->nama_jabatan;
        $this->deskripsi = $jabatan->deskripsi;
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        $jabatan = Jabatan::find($id);

        // Cek apakah jabatan masih digunakan
        if ($jabatan->mahasiswa()->count() > 0) {
            session()->flash('error', 'Jabatan tidak dapat dihapus karena masih digunakan oleh mahasiswa!');
            return;
        }

        $jabatan->delete();
        session()->flash('message', 'Jabatan berhasil dihapus!');
    }

    public function render()
    {
        $jabatans = Jabatan::where('nama_jabatan', 'like', '%' . $this->search . '%')
            ->withCount('mahasiswa')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.jabatan.index', [
            'jabatans' => $jabatans,
        ]);
    }
}
