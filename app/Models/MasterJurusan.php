<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJurusan extends Model
{
    use HasFactory;
    protected $table="master_kelas";

    public function hitung()
    {
        return $this->hasMany(MasterSiswa::class,'jurusan_id')->count();
    }

    public function mapel()
    {
        return $this->hasMany(MasterMapel::class,'jurusan_id')->orderby('kelompok','asc');
    }
}
