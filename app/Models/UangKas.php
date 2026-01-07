<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UangKas extends Model
{
    protected $table = 'uang_kas';
    protected $fillable = ['mahasiswa_id', 'jumlah', 'tanggal_bayar', 'bulan', 'tahun', 'keterangan', 'user_id'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
