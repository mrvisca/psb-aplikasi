<?php

namespace App\Imports;

use App\Models\MasterMapel;
use App\Models\MasterSiswa;
use App\Models\RaporSiswa;
use App\Models\TahunAjar;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportRaporSiswa implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function rules(): array
    {
        return [
            'nama' => 'required',
            'mata_pelajaran' => 'required',
            'kelompok' => 'required',
            'tipe' => 'required',
            'nilai' => 'required',
            'jurusan' => 'required',
            'semester' => 'required',
            'tahun_ajar' => 'required',
        ];
    }
 
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Master Siswa
            $siswa = MasterSiswa::with('jurusan')->whereHas('jurusan', function ($q) use ($row) {
                return $q->where('name','LIKE','%'.$row['jurusan'].'%');
            })->where('name','LIKE','%'.$row['nama'].'%')->first();
            $mapel = MasterMapel::where('name','LIKE','%'.$row['mata_pelajaran'].'%')->where('kelompok',$row['kelompok'])->where('type',$row['tipe'])->first();
            $tajar = TahunAjar::where('name','LIKE','%'.$row['tahun_ajar'].'%')->where('semester',$row['semester'])->first();
            if($siswa && $mapel && $tajar)
            {
                $rapor = RaporSiswa::updateOrCreate([
                    'tajar_id' => $tajar->id,
                    'mapel_id' => $mapel->id,
                    'siswa_id' => $siswa->id,
                ],[
                    'tajar_id' => $tajar->id,
                    'mapel_id' => $mapel->id,
                    'siswa_id' => $siswa->id,
                    'nilai' => $row['nilai'],
                ]);
            }
        }
    }
}
