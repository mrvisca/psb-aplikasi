<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

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
    }
}
