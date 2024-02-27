<?php

namespace App\Exports;

use App\Models\MasterMapel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class TemplateMasterMapelExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    function array(): array
    {
        $mapel = MasterMapel::where(function($q) {
            return $q;
        })->take(1)->get();
        if ($mapel->isNotEmpty())
        {
            foreach ($mapel as $m)
            {
                $item['nama'] = $m->name;
                $item['kelompok'] = 'Kelompok A/Kelompok B/ Kelompok C';
                $item['type'] = 'Pengetahuan/Keterampilan';
                $item['kelas'] = '10 MIPA 1/ 10 MIPA 2/ DST';
                $data[] = $item;
            }
        }else{
            $data = [];
        }

        return $data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:D1'; // All headers
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
            'Kelompok',
            'Type',
            'Kelas',
        ];
    }
}
