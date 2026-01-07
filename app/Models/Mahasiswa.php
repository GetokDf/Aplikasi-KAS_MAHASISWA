<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $fillable = ['nim', 'nama', 'jabatan_id', 'email', 'no_hp', 'status'];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function uangKas()
    {
        return $this->hasMany(UangKas::class);
    }
}
