<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterguruController extends Controller
{
    public function index()
    {
        return view('admin.masterguru.index');
    }
}
