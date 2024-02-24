<?php

namespace App\Http\Controllers;

use App\Exports\MasterguruExport;
use App\Imports\MasterguruImport;
use App\Exports\TemplateMasterguruExport;
use App\Models\Role;
use App\Models\User;
use App\Models\MasterGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class MasterguruController extends Controller
{
    public function index()
    {
        return view('admin.masterguru.index');
    }

    public function listGuru(Request $request)
    {
        $columns = [
            0 => 'id',
            1 => 'nip',
            2 => 'user_id',
            4 => 'jabatan',
            5 => 'status',
        ];

        $start = $request->start;
        $limit = $request->length;
        $orderColumn = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search')['value'];

        // Hitunga keseluruhan
        $hitung = MasterGuru::with('user')->whereHas('user', function ($q){
            return $q->where('role_id','>',2);
        })->count();

        $masgu = MasterGuru::with('user')->whereHas('user', function ($q){
            return $q->where('role_id','>',2);
        })->where(function ($q) use ($search) {
            if($search != null)
            {
                return $q->where('jabatan','LIKE','%'.$search.'%')->orWhere(function ($queri) use ($search) {
                    return $queri->whereHas('user', function ($kueri) use ($search) {
                        return $kueri->where('name','LIKE','%'.$search.'%');
                    });
                })->orWhere('nip',$search);
            }
        })->orderby($orderColumn,$dir)->skip($start)->take($limit)->get();
        $data = array();
        foreach($masgu as $m)
        {
            $item['id'] = $m->id;
            $item['user_id'] = $m->user_id;
            $item['user_name'] = $m->user->name ?? '';
            $item['nip'] = $m->nip;
            $item['jenkel'] = $m->jenkel;
            $item['role_name'] = $m->user->role->name ?? '';
            $item['jabatan'] = $m->jabatan;
            $item['setatus'] = $m->status == 1 ? 'Aktif' : 'Nonaktif';
            $item['status'] = $m->status;
            $item['telpon'] = $m->telpon;
            $data[] = $item;
        }

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $hitung,
            'recordsFiltered' => $hitung,
            'data' => $data,
        ],200);
    }

    public function addMaster(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'nip' => 'required',
            'role_id' => 'required',
            'jenkel' => 'required',
            'jabatan' => 'required',
            'telpon' => 'required',
            'status' => 'required',
        ]);

        //response error validation
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        // Jika Foto tersedia
        if ($request->hasFile('photo')) {
            // Fungsi upload photo
            $gambar = $request->file('photo');
            $nama_gambar = time() . '.' . $gambar->getClientOriginalExtension();

            // Menggunakan Image Manager untuk membuat objek gambar
            $img = Image::make($gambar);

            // Mengompres gambar dengan kualitas bagus dan target ukuran di bawah 100KB
            $img->encode('jpg', 60); // Ubah format dan kualitas kompresi di sini

            // Mendapatkan ukuran file setelah kompresi
            $fileSize = $img->filesize();

            // Simpan gambar ke direktori penyimpanan
            $path = public_path("assets/guru/{$nama_gambar}");
            $img->save($path);
            $simpan = 'assets/guru/'.$nama_gambar;

            // Buat User Login untuk master guru
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->role_id = $request->role_id;
            $user->is_active = $request->status;
            $user->save();
            
            // Buat Profile User master guru
            $guru = new MasterGuru();
            $guru->user_id = $user->id;
            $guru->nip = $request->nip;
            $guru->jenkel = $request->jenkel;
            $guru->jabatan = $request->jabatan;
            $guru->telpon = $request->telpon;
            $guru->photo = $simpan;
            $guru->status = $request->status;
            $guru->save();

            // Kirim Email ke pengguna untuk kredensial mereka
            $user = [
                'email' => $request->email,
                'name' => $request->name,
            ];
            Mail::send('fmail.gururegister', ['nama' => $request->name, 'email' => $request->email, 'password' => $request->pasword, 'telpon' => $request->telpon, 'jabatan' => $request->jabatan], function ($m) use ($user) {
                $m->from('dodo@mrvisca.tech', 'Mr Visca');
                $m->to($user['email'], $user['name'])->subject('Email pendaftaran akun!');
            });

            return response()->json([
                'success' => true,
                'message' => 'Berhasil membuat data master guru baru',
            ],201);
        }else{
            // Buat User Login untuk master guru
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->role_id = $request->role_id;
            $user->is_active = true;
            $user->save();
            
            // Buat Profile User master guru
            $guru = new MasterGuru();
            $guru->user_id = $user->id;
            $guru->nip = $request->nip;
            $guru->jenkel = $request->jenkel;
            $guru->jabatan = $request->jabatan;
            $guru->telpon = $request->telpon;
            $guru->status = $request->status;
            $guru->save();

            // Kirim Email ke pengguna untuk kredensial mereka
            $user = [
                'email' => $request->email,
                'name' => $request->name,
            ];
            Mail::send('fmail.gururegister', ['nama' => $request->name, 'email' => $request->email, 'password' => $request->pasword, 'telpon' => $request->telpon, 'jabatan' => $request->jabatan], function ($m) use ($user) {
                $m->from('dodo@mrvisca.tech', 'Mr Visca');
                $m->to($user['email'], $user['name'])->subject('Email pendaftaran akun!');
            });

            return response()->json([
                'success' => true,
                'message' => 'Berhasil membuat data master guru baru',
            ],201);
        }
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
        ],200);
    }

    public function updateGuru(Request $request, $id)
    {
        $find = MasterGuru::where('id',$id)->first();
        if(!$find)
        {
            return response()->json([
                'success' => false,
                'message' => 'Update data gagal, data guru tidak ditemukan',
            ],400);
        }else{
            // Update status user login
            $user = User::where('id',$find->user_id)->first();
            if($user)
            {
                $user->is_active = $request->status;
                $user->save();

                // Update data master guru
                $find->nip = $request->nip;
                $find->jenkel = $request->jenkel;
                $find->jabatan = $request->jabatan;
                $find->telpon = $request->telpon;
                $find->status = $request->status;
                $find->save(); 

                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil update data guru',
                ],201);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat update data guru',
                ],400);
            }
        }
    }

    public function hapus($id)
    {
        $find = MasterGuru::where('id',$id)->first();
        if(!$find)
        {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus data guru, data tidak ditemukan',
            ],400);
        }else{
            $user = User::where('id',$find->user_id)->delete();
            $hapus_guru = MasterGuru::where('id',$find->id)->delete();

            if($user && $hapus_guru)
            {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menghapus data guru',
                ],201);
            }else{
                return response()->json([
                    'success' => true,
                    'message' => 'Terjadi kesalahan saat menghapus master guru',
                ],400);
            }
        }
    }

    public function exportData()
    {
        $master = MasterGuru::with('user')->whereHas('user', function ($q) {
            return $q->where('role_id','>',2);
        })->orderby('id','desc')->get();
        $data = array();
        foreach($master as $m)
        {
            $item['nip'] = $m->nip;
            $item['name'] = $m->user->name ?? '';
            $item['email'] = $m->user->email ?? '';
            $item['jenkel'] = $m->jenkel;
            $item['role'] = $m->user->role->name ?? '';
            $item['jabatan'] = $m->jabatan;
            $item['telpon'] = $m->telpon;
            $data[] = $item;
        }

        return Excel::download(new MasterguruExport($data), 'Master-Guru-Export.xlsx');
    }

    public function template()
    {
        return Excel::download(new TemplateMasterguruExport(), 'Template-Master-Guru.xlsx');
    }

    public function import(Request $request)
    {
        // Lakukan validasi data impor
        $request->validate([
            'excel' => 'required|mimes:xls,xlsx',
        ]);

        // Proses data impor
        $file = $request->file('excel');

        Excel::import(new MasterguruImport, $file);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil melakukan import data master guru'
        ],201);
    }
}
