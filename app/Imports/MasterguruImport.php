<?php

namespace App\Imports;

use App\Models\MasterGuru;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterguruImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */

    public function rules(): array
    {
        return [
            'nip' => 'required',
            'nama' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required',
            'role' => 'required',
            'jabatan' => 'required',
            'telpon' => 'required',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Cari Data Role
            $find = Role::where('name','LIKE','%'.$row['role'].'%')->first();
            if($find)
            {
                // Create User
                $user = new User();
                $user->name = $row['nama'];
                $user->email = $row['email'];
                $user->password = Hash::make($row['password']);
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->role_id = $find->id;
                $user->is_active = 1;
                $user->save();

                // Buat Profil Guru
                $guru = new MasterGuru();
                $guru->user_id = $user->id;
                $guru->nip = $row['nip'];
                $guru->jenkel = $row['jenkel'];
                $guru->jabatan = $row['jabatan'];
                $guru->telpon = $row['telpon'];
                $guru->status = 1;
                $guru->save();
            }
        }
    }
}
