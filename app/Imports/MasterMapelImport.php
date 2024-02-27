<?php

namespace App\Imports;

use App\Models\MasterMapel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterMapelImport implements ToCollection, WithHeadingRow
{
    public function rules(): array
    {
        return [
            'nama' => 'required',
            'kelompok' => 'required',
            'type' => 'required',
            'kelas' => 'required',
        ];
    }

    public function collection(Collection $rows)
    {
        // dd($rows)->toArray();

        foreach ($rows as $row)
        {
            $mapel = new MasterMapel();
            $mapel->name = $row['nama'];
            $mapel->kelompok = $row['kelompok'];
            $mapel->type = $row['type'];
            $mapel->kelas = $row['kelas'];
            $mapel->save();

        }

    }

}
