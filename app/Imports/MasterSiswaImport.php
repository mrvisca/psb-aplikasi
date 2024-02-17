<?php

namespace App\Imports;

use App\Models\MasterSiswa;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MasterSiswaImport implements ToCollection, WithHeadingRow
{
    public function rules(): array
    {
        return [
            'nis' => 'required',
            'nama' => 'required',
            'jurusan' => 'required',
            'jenkel' => 'required',
            'kelas' => 'required',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row){

            // cari data role
            // $find = Role::where('name', 'LIKE', '%'.$row['role'].'%')->first();
            // if($find)
            // {
            //     // $user = new User();
            //     $siswa = new MasterSiswa();
            //     // $siswa->user_id = $user->id;
            //     $siswa->nis = $row['nis'];
            //     $siswa->name = $row['name'];
            //     $siswa->jurusan = $row['jurusan'];
            //     $siswa->jenkel = $row['jenkel'];
            //     $siswa->kelas = $row['kelas'];
            //     $siswa->save();
            // }
            $user = new User();
            $user->name = $row['nama'];

            $siswa = new MasterSiswa();
            $siswa->nis = $row['nis'];
            $siswa->name = $row['nama'];
            $siswa->jurusan = $row['jurusan'];
            $siswa->jenkel = $row['jenkel'];
            $siswa->kelas = $row['kelas'];

            $user->save();
            $siswa->save();

        }
    }
}
