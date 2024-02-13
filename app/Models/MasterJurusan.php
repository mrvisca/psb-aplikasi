<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJurusan extends Model
{
    use HasFactory;

    public function hitung()
    {
        return $this->hasMany(MasterSiswa::class,'jurusan_id')->count();
    }
}
