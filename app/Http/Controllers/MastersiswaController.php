<?php

namespace App\Http\Controllers;

use App\Exports\MasterSiswaExport;
use App\Exports\TemplateMasterSiswaExport;
use App\Imports\MasterSiswaImport;
use App\Models\MasterSiswa;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            0 => 'id',
            1 => 'nis',
            2 => 'user_id',
            3 => 'jurusan_id',
            4 => 'jenkel',
            5 => 'kelas',
        ];

        $start = $request->start;
        $limit = $request->length;
        $orderColumn = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search')['value'];

        // Hitunga keseluruhan
        $hitung = MasterSiswa::with('user')->whereHas('user', function ($q){
            return $q->where('role_id','==',1);
        })->count();

        $siswa = MasterSiswa::with('user')->whereHas('user', function ($q){
            return $q->where('role_id','==',1);
        })->where(function ($q) use ($search) {
            if($search != null)
            {
                return $q->where('nis','ILIKE','%'.$search.'%')->orWhere(function ($queri) use ($search) {
                    return $queri->whereHas('user', function ($kueri) use ($search) {
                        return $kueri->where('name','ILIKE','%'.$search.'%');
                    });
                })->orwhere('nis', $search);
            }
        })->orderby($orderColumn, $dir)->skip($start)->take($limit)->get();
        $data = array();
        foreach($siswa as $s)
        {
            $item['id'] = $s->id;
            $item['nis'] = $s->nis;
            $item['user_id'] = $s->user_id;
            $item['user_name'] = $s->user->name ?? '';
            $item['jurusan'] = $s->jurusan;
            $item['jurusan_name'] = $s->jurusan->name ?? '';
            $item['jenkel'] = $s->jenkel;
            $item['kelas'] = $s->kelas;
            $data[] = $item;
        }

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $hitung,
            'recordsFiltered' => $hitung,
            'data' => $data,
        ], 200);
    }

    public function supportRole()
    {
        $user = Role::where('id','!=',1)->where('id','!=',2)->orderby('id','desc')->get();
        $data = array();
        foreach($user as $u)
        {
            $item['id'] = $u->id;
            $item['name'] = $u->name;
            $data[] = $item;
        }

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function addMaster(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nis' => 'required',
            'name' => 'required',
            'jurusan_id' => 'required',
            'jenkel' => 'required',
            'kelas' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->save();

        $siswa = new MasterSiswa();
        // $siswa->user_id = $user->id;
        $siswa->nis = $request->nis;
        $siswa->name = $request->name;
        $siswa->jurusan_id = $request->jurusan;
        $siswa->jenkel = $request->jenkel;
        $siswa->kelas = $request->kelas;
        $siswa->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat data master siswa baru',
        ], 201);
    }

    public function updateSiswa(Request $request, $id)
    {
        $find = MasterSiswa::where('id', $id)->first();
        if(!$find)
        {
            return response()->json([
                'success' => false,
                'message' => 'Update data gagal, data siswa tidak ditemukan',
            ], 400);
        }else{
            $find->nis = $request->nis;
            $find->name = $request->name;
            $find->jurusan = $request->jurusan;
            $find->jenkel = $request->jenkel;
            $find->kelas = $request->kelas;
        }
    }

    public function deleteSiswa(Request $request, $id)
    {
        $find = MasterSiswa::where('id', $id)->first();
        if(!$find)
        {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus data siswa, data tidak ditemukan',
            ], 400);
        }else{
            $hapus = MasterSiswa::where('id', $find->id)->delete();

            if($hapus)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menghapus data siswa',
                ], 201);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'Terjadi kesalahan saat menghapus master siswa',
                ], 400);
            }
        }
    }

    public function exportData()
    {
        $export = MasterSiswa::with('user')->whereHas('user', function ($q) {
            return $q->where('role_id', '==', 1);
        })->orderby('id', 'desc')->get();
        $data = array();
        foreach($export as $e)
        {
            $item['nis'] = $e->nis;
            $item['name'] = $e->user->name ?? '';
            $item['jurusan'] = $e->jurusan->name ?? '';
            $item['jenkel'] = $e->jenkel;
            $item['kelas'] = $e->kelas;
            $data[] = $item;
        }

        return Excel::download(new MasterSiswaExport($data), 'Master-Siswa-Export.xlsx');
    }

    public function template()
    {
        return Excel::download(new TemplateMasterSiswaExport(), 'Template-Master-Siswa.xlsx');
    }

    public function importData(Request $request)
    {
        //validasi import
        $request->validate([
            'excel' => 'required|mimes:xlsx, xls',
        ]);

        //proses import
        $file = $request->file('excel');

        Excel::import(new MasterSiswaImport, $file);


        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengimport data master siswa',
        ], 201);
    }
}
