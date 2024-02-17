<?php

namespace App\Exports;

use App\Models\MasterSiswa;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;


class TemplateMasterSiswaExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function array(): array
    {
        // $siswa = MasterSiswa::with('user')->whereHas('user', function ($q) {
        //     return $q->where('role_id','=',1);
        // })->take(1)->get();
        // if($siswa->isNotEmpty()) {
        //     foreach($siswa as $s)
        //     {
                
        //     }
        // }else{
        //     $data = [];
        // }
        $item['nis'] = '202301';
        $item['nama'] = 'Mukti';
        $item['jurusan'] = 'IPA/IPS';
        $item['jenkel'] = 'laki-laki/perempuan';
        $item['kelas'] = '10 IPA 1/10 IPA 2';
        $data[] = $item;

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:E1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000'],
                        ],
                    ],

                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ],

                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => 'B8860B'],
                    ]
                ];
            },
        ];
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama',
            'Jurusan',
            'Jenkel',
            'Kelas',
        ];
    }
}
