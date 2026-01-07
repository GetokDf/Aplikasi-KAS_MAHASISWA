<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';
    protected $fillable = ['nama_pengeluaran', 'jumlah', 'tanggal', 'keterangan', 'bukti', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
