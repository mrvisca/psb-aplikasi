<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSiswa extends Model
{
    use HasFactory;
    protected $table = 'master_siswas';
    protected $fillable = ['jurusan_id','name','kelompok','type'];

    public function jurusan()
    {
        return $this->belongsTo(MasterJurusan::class, 'jurusan_id');
    }
}
