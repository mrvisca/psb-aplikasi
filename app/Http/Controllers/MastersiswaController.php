<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MastersiswaController extends Controller
{
    public function index()
    {
        return view('admin.mastersiswa.index');
    }
}
