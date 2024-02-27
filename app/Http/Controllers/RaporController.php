<?php

namespace App\Http\Controllers;

use App\Exports\RaporSiswaTemplate;
use App\Models\MasterSiswa;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RaporController extends Controller
{
    public function testRapor()
    {
        // Fungsi Template Export dan detail rapor siswa
        $siswa = MasterSiswa::all();
        $data = array();
        foreach($siswa as $s)
        {
            foreach($s->jurusan->mapel as $m)
            {
                $find = TahunAjar::where('tahun',date('Y'))->first();
                if($find)
                {
                    $item['nama_siswa'] = $s->name;
                    $item['nama_mapel'] = $m->name;
                    $item['kelompok_mapel'] = $m->kelompok;
                    $item['type_mapel'] = $m->type;
                    $item['nilai'] = 80;
                    $item['jurusan'] = $s->jurusan->name ?? '';
                    $item['semester'] = $find->semester;
                    $item['tahun_ajar'] = $find->name;
                    $data[] = $item;
                }
            }
        }

        return response()->json([
            'data' => $data,
        ],200);
    }

    public function index()
    {
        return view('admin.data_nilai.rapor.index'); 
    }

    public function listRapor(Request $request)
    {
        // Data / function dummy
        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'name',
            3 => 'name',
            4 => 'name',
        ];

        $start = $request->start;
        $limit = $request->length;
        $orderColumn = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search')['value'];

        // Hitunga keseluruhan
        $hitung = MasterSiswa::count();

        $siswa = MasterSiswa::where(function ($q) use ($search) {
            if($search != null)
            {
                return $q->where('name','LIKE','%'.$search.'%');
            }
        })->orderby($orderColumn, $dir)->skip($start)->take($limit)->get();
        $data = array();
        foreach($siswa as $s)
        {
            $item['id'] = $s->id;
            $item['name'] = $s->name;
            $item['pengetahuan'] = 80;
            $item['keterampilan'] = 80;
            $item['rapor'] = 80;
            $data[] = $item;
        }

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $hitung,
            'recordsFiltered' => $hitung,
            'data' => $data,
        ], 200);
    }

    public function supportTajar()
    {
        $tajar = TahunAjar::all();
        $data = array();
        foreach($tajar as $t)
        {
            $item['id'] = $t->id;
            $item['name'] = $t->name;
            $data[] = $item;
        }

        return response()->json([
            'data' => $data,
        ],200);
    }

    public function downloadTemplate(Request $request)
    {
        return Excel::download(new RaporSiswaTemplate($request->tajar), 'Template-Rapor-Siswa.xlsx');
    }
}
