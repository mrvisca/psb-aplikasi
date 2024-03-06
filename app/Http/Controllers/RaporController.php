<?php

namespace App\Http\Controllers;

use App\Exports\RaporSiswaTemplate;
use App\Imports\ImportRaporSiswa;
use App\Models\MasterSiswa;
use App\Models\RaporSiswa;
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
        $takhir = $request->tahun_ajar;
        $tajar = TahunAjar::where(function ($q) use ($takhir) {
            if($takhir)
            {
                return $q->where('id',$takhir);
            }else{
                return $q->where('tahun',date('Y'));
            }
        })->first();

        // Hitunga keseluruhan
        $hitung = MasterSiswa::where(function ($q) use ($tajar) {
            if($tajar != null)
            {
                return $q->where('tahun_akhir','>=',$tajar->tahun);
            }
        })->count();

        $siswa = MasterSiswa::where(function ($q) use ($search) {
            if($search != null)
            {
                return $q->where('name','LIKE','%'.$search.'%');
            }
        })->where(function ($q) use ($tajar) {
            if($tajar != null)
            {
                return $q->where('tahun_akhir','>=',$tajar->tahun);
            }
        })->orderby($orderColumn, $dir)->skip($start)->take($limit)->get();
        $data = array();
        foreach($siswa as $s)
        {
            $nilai = RaporSiswa::where('tajar_id',$tajar->id)->where('siswa_id',$s->id)->get();
            $pengetahuan = 0;
            $keterampilan = 0;
            $cek = array();
            foreach($nilai as $n)
            {
                $mapel = $n->mapel->type ?? null;
                if($mapel != null && $mapel == 'Nilai Pengetahuan')
                {
                    $pengetahuan += $n->nilai;
                }elseif($mapel != null && $mapel == 'Nilai Keterampilan'){
                    $keterampilan += $n->nilai;
                }
            }

            $hasil_pen = 0;
            $hasil_ket = 0;
            $final = 0;
            if($pengetahuan >= 18 && $keterampilan >= 18)
            {
                $hasil_pen = $pengetahuan / 18;
                $hasil_ket = $keterampilan / 18;
                $final = $hasil_pen + $hasil_ket / 2;
            }

            $item['id'] = $s->id;
            $item['name'] = $s->name;
            $item['pengetahuan'] = $hasil_pen;
            $item['keterampilan'] = $hasil_ket;
            $item['rapor'] = $final;
            $item['nilai'] = $pengetahuan.' '.$keterampilan;
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
        $tajar = TahunAjar::orderby('tahun','desc')->get();
        $data = array();
        foreach($tajar as $t)
        {
            $item['id'] = $t->id;
            $item['name'] = $t->name;
            $item['tahun'] = $t->tahun;
            $item['semester'] = $t->semester;
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

    public function import(Request $request)
    {
        // Lakukan validasi data impor
        $request->validate([
            'excel' => 'required|mimes:xls,xlsx',
        ]);

        // Proses data impor
        $file = $request->file('excel');

        Excel::import(new ImportRaporSiswa, $file);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil melakukan import data master guru'
        ],201);
    }
}
