<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Endar Dharma Mukti',
                'email' => 'endardharma1@gmail.com',
                'password' => Hash::make('11223344'),
                'role_id' => 1,
                'email_verified_at' => '2023-11-25 23:08:00',
                'is_active' => true,
                'created_at' => '2023-11-25 23:08:00',
            ],
        ];

        User::insert($data);
    }
}
