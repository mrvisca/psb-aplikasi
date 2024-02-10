<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function pageConstruction()
    {
        return view('admin.construction.index');
    }

    public function getProfile()
    {
        // Cek Role
        $find = Role::where('id',Auth::user()->role_id)->first();

        $data = [
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'role_id' => Auth::user()->role_id,
            'role_name' => $find->name == null ? 'Nama Role Tidak Tersedia' : $find->name,
            'data' => $find,
        ];

        return response()->json($data, 200);
    }
}
