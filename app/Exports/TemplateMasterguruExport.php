<?php

namespace App\Exports;

use App\Models\MasterGuru;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class TemplateMasterguruExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    function array(): array
    {
        $master = MasterGuru::with('user')->whereHas('user', function ($q) {
            return $q->where('role_id','>',2);
        })->take(1)->get();
        if ($master->isNotEmpty()) {

            foreach ($master as $m)
            {
                $item['nip'] = $m->nip;
                $item['nama'] = $m->user->name ?? '';
                $item['email'] = 'user@mail.com';
                $item['password'] = 'password';
                $item['jenkel'] = 'laki-laki/perempuan';
                $item['role'] = 'Wali Kelas/Guru BK/Admin Raport/Bagian Kurikulum/Bagian Tata Usaha/Guru Agama';
                $item['jabatan'] = $m->jabatan;
                $item['telpon'] = '6282140466335';
                $data[] = $item;
            }
        } else {
            $data = [];
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
            'NIP',
            'Nama',
            'Email',
            'Password',
            'Jenkel',
            'Role',
            'Jabatan',
            'Telpon',
        ];
    }
}
