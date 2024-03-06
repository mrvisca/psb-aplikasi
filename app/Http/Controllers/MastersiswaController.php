<?php

namespace App\Http\Controllers;

use App\Exports\MastersiswaExport;
use App\Exports\TemplateMastersiswa;
use App\Imports\MastersiswaImport;
use App\Models\MasterJurusan;
use App\Models\MasterSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MastersiswaController extends Controller
{
    public function index()
    {
        return view('admin.mastersiswa.index');
    }

    public function listSiswa(Request $request)
    {
        $columns = [
            0 => 'nis',
            1 => 'name',
            2 => 'jenkel',
            3 => 'jurusan_id',
            4 => 'email',
            5 => 'telpon',
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
                return $q->where('nis','LIKE','%'.$search.'%')->orWhere('name','LIKE','%'.$search.'%')->orwhere('email','LIKE','%'.$search.'%')->orWhere('telpon',$search);
            }
        })->orderby($orderColumn, $dir)->skip($start)->take($limit)->get();
        $data = array();
        foreach($siswa as $s)
        {
            $item['id'] = $s->id;
            $item['nis'] = $s->nis;
            $item['name'] = $s->name;
            $item['email'] = $s->email;
            $item['jurusan_id'] = $s->jurusan_id;
            $item['jurusan_name'] = $s->jurusan->name ?? '';
            $item['jenkel'] = $s->jenkel;
            $item['telpon'] = $s->telpon;
            $item['periode'] = $s->periode;
            $data[] = $item;
        }

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $hitung,
            'recordsFiltered' => $hitung,
            'data' => $data,
        ], 200);
    }

    public function createUser(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:master_siswas',
            'nis' => 'required',
            'jurusan_id' => 'required',
            'jenkel' => 'required',
            'telpon' => 'required',
            'periode' => 'required',
        ]);

        //response error validation
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        // Tahun Akhir Siswa
        $takhir = (int)$request->periode + 3;

        $siswa = new MasterSiswa();
        $siswa->nis = $request->nis;
        $siswa->name = $request->name;
        $siswa->email = $request->email;
        $siswa->jurusan_id = $request->jurusan_id;
        $siswa->jenkel = $request->jenkel;
        $siswa->telpon = $request->telpon;
        $siswa->periode = $request->periode;
        $siswa->tahun_akhir = $takhir;
        $siswa->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data siswa baru',
        ],201);
    }

    public function listJurusan()
    {
        $kelas = MasterJurusan::all();
        $data = array();
        foreach($kelas as $k)
        {
            $item['id'] = $k->id;
            $item['name'] = $k->name;
            $item['kode'] = $k->kode;
            $data[] = $item;
        }

        return response()->json([
            'data' => $data,
        ],200);
    }

    public function updateData(Request $request,$id)
    {
        $find = MasterSiswa::where('id',$id)->first();
        if($find)
        {
            $request->nis != null ? $find->nis = $request->nis : true;
            $request->name != null ? $find->name = $request->name : true;
            $request->email != null ? $find->email = $request->email : true;
            $request->jurusan_id != null ? $find->jurusan_id = $request->jurusan_id : true;
            $request->jenkel != null ? $find->jenkel = $request->jenkel : true;
            $request->telpon != null ? $find->telpon = $request->telpon : true;
            $request->periode != null ? $find->periode = $request->periode : true;
            $find->save();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil update data master siswa',
            ],201);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Update data siswa gagal, data tidak ditemukan',
            ],400);
        }
    }

    public function hapus($id)
    {
        $hapus = MasterSiswa::where('id',$id)->delete();
        if($hapus)
        {
            return response()->json([
                'success' => true,
                'message' => 'Hapus data master siswa berhasil',
            ],201);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data master siswa',
            ],400);
        }
    }
    
    public function exportData()
    {
        $master = MasterSiswa::all();
        $data = array();
        foreach($master as $m)
        {
            $item['id'] = $m->id;
            $item['nis'] = $m->nis;
            $item['name'] = $m->name;
            $item['email'] = $m->email;
            $item['kelas'] = $m->jurusan->name ?? '';
            $item['jenkel'] = $m->jenkel;
            $item['telpon'] = $m->telpon;
            $item['periode'] = $m->periode;
            $data[] = $item;
        }

        return Excel::download(new MastersiswaExport($data), 'Master-Siswa-Export.xlsx');
    }

    public function template()
    {
        return Excel::download(new TemplateMastersiswa(), 'Template-Master-Guru.xlsx');
    }

    public function import(Request $request)
    {
        // Lakukan validasi data impor
        $request->validate([
            'excel' => 'required|mimes:xls,xlsx',
        ]);

        // Proses data impor
        $file = $request->file('excel');

        Excel::import(new MastersiswaImport, $file);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil melakukan import data master guru'
        ],201);
    }
}
