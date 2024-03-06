<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaporSiswa extends Model
{
    use HasFactory;
    protected $fillable = ['tajar_id','mapel_id','siswa_id','nilai'];

    public function mapel()
    {
        return $this->belongsTo(MasterMapel::class,'mapel_id');
    }
}
