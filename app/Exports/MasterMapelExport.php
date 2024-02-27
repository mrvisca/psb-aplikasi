<?php

namespace App\Exports;

use App\Models\MasterMapel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class MasterMapelExport implements FromView
{
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('export.mastermapel', [
            'data' => $this->data,
        ]);
    }
}
