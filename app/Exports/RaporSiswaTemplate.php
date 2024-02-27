<?php

namespace App\Exports;

use App\Models\MasterSiswa;
use App\Models\TahunAjar;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class RaporSiswaTemplate implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    
    function array(): array
    {
        // Fungsi Template Export dan detail rapor siswa
        $siswa = MasterSiswa::all();
        $data = array();
        foreach($siswa as $s)
        {
            foreach($s->jurusan->mapel as $m)
            {
                $find = TahunAjar::where('id',$this->data)->first();
                if($find)
                {
                    $item['nama_siswa'] = $s->name;
                    $item['nama_mapel'] = $m->name;
                    $item['kelompok_mapel'] = $m->kelompok;
                    $item['type_mapel'] = $m->type;
                    $item['nilai'] = 'Isi nilai dengan angka';
                    $item['jurusan'] = $s->jurusan->name ?? '';
                    $item['semester'] = $find->semester;
                    $item['tahun_ajar'] = $find->name;
                    $data[] = $item;
                }
            }
        }

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:H1'; // All headers
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
                        'color' => ['argb' => 'B8860B']
                    ]
                ];

            },
        ];

    }

    public function headings(): array
    {
        return [
            'Nama',
            'Mata Pelajaran',
            'Kelompok',
            'Tipe',
            'Nilai',
            'Jurusan',
            'Semester',
            'Tahun Ajar',
        ];
    }
}
