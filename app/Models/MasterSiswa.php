<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSiswa extends Model
{
    use HasFactory;
    protected $table = 'master_siswas';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function jurusan()
    {
        return $this->belongTo(MasterJurusan::class, 'jurusan_id');
    }
}
