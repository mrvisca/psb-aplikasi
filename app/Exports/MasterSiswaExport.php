<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MasterSiswaExport implements FromView
{
    public $data;
    public function __contruct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('export.mastersiswa', [
            'data' => $this->data
        ]);
    }

}
