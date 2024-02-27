<?php

namespace App\Http\Controllers;

use App\Exports\MasterMapelExport;
use App\Exports\TemplateMasterMapelExport;
use App\Imports\MasterMapelImport;
use App\Models\MasterMapel;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MasterMapelController extends Controller
{
    public function index()
    {
        return view ('admin.mastermapel.index');
    }

    public function supportRole()
    {
        $user = Role::where('id', '!=', 1)->where('id', '!=', 2)->orderby('id', 'desc')->get();
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

    public function listMapel(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'kelompok',
            3 => 'type',
            4 => 'kelas',
        ];

        $start = $request->start;
        $limit = $request->length;
        $orderColumn = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search')['value'];

        //hitung keseluruhan
        $hitung = MasterMapel::where(function ($q) {
            return $q;
        })->count();

        // $hitung = MasterMapel::count();

        $mapel = MasterMapel::where(function ($q) {
            return $q;
        })->where(function ($q) use ($search) {
            if($search != null)
            {
                return $q->where('name','ILIKE','%'.$search.'%')->orWhere(function ($queri) use ($search) {
                    return $queri->whereHas('user', function ($kueri) use ($search) {
                        return $kueri->where('name','ILIKE','%'.$search.'%');
                    });
                })->orWhere('kelompok', $search);
            }
        })->orderby($orderColumn, $dir)->skip($start)->take($limit)->get();
        $data = array();
        foreach($mapel as $m)
        {
            $item['id'] = $m->id;
            $item['name'] = $m->name;
            $item['kelompok'] = $m->kelompok;
            $item['type'] = $m->type;
            $item['kelas'] = $m->kelas;
            $data[] = $item;
        }

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $hitung,
            'recordsFiltered' => $hitung,
            'data' => $data
        ], 200);
    }

    public function addMaster(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'kelompok' => 'required',
            'type' => 'required',
            'kelompok' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        $mapel = new MasterMapel();
        $mapel->name = $request->name;
        $mapel->kelompok = $request->kelompok;
        $mapel->type = $request->type;
        $mapel->kelas = $request->kelas;
        $mapel->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat data master mapel baru',
        ], 201);
    }

    public function updateMapel(Request $request, $id)
    {
        $find = MasterMapel::where('id', $id)->first();
        if(!$find)
        {
            return response()->json([
                'success' => false,
                'message' => 'Update data gagal, data mapel tidak ditemukan',
            ], 400);
        }else{
            $find->name = $request->name;
            $find->kelompok = $request->kelompok;
            $find->type = $request->type;
            $find->kelas = $request->kelas;
        }
    }

    public function deleteMapel(Request $request, $id)
    {
        $find = MasterMapel::where('id', $id)->first();
        if(!$find)
        {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus data mapel, data tidak ditemukan',
            ], 400);
        }else{
            $hapus = MasterMapel::where('id', $find->id)->delete();

            if($hapus)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menghapus data mapel',
                ],201);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'Terjadi kesalahan saat menghapus master mapel',
                ], 400);
            }
        }
    }

    public function exportData()
    {
        $export = MasterMapel::where(function ($q) {
            return $q;
        })->orderby('id', 'desc')->get();
        $data = array();
        foreach($export as $e)
        {
            $item['name'] = $e->name;
            $item['kelompok'] = $e->kelompok;
            $item['type'] = $e->type;
            $item['kelas'] = $e->kelas;
            $data[] = $item;
        }

        return Excel::download(new MasterMapelExport($data), 'Master-Mapel-Export.xlsx');
    }

    public function template()
    {
        return Excel::download(new TemplateMasterMapelExport(), 'Template-Master-Mapel.xlsx');
    }

    public function importData(Request $request)
    {
        // validation import
        $request->validate([
            'excel' => 'required|mimes:xlsx, xls',
        ]);

        // proses import
        $file = $request->file('excel');

        Excel::import(new MasterMapelImport, $file);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengimport data master mapel',
        ], 201);
    }
}
