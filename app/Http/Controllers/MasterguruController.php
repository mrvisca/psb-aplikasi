<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\MasterGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
        $hitung = MasterGuru::count();

        $masgu = MasterGuru::with('user')->where(function ($q) use ($search) {
            if($search != null)
            {
                return $q->where('jabatan','ILIKE','%'.$search.'%')->orWhere(function ($queri) use ($search) {
                    return $queri->whereHas('user', function ($kueri) use ($search) {
                        return $kueri->where('name','ILIKE','%'.$search.'%');
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
            $item['status'] = $m->status;
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
            Mail::send('fmail.register', ['nama' => $request->name, 'email' => $request->email, 'password' => $request->pasword, 'telpon' => $request->telpon, 'jabatan' => $request->jabatan], function ($m) use ($user) {
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
}
