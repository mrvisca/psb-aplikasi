<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
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
                'name' => 'Team IT',
                'desc' => 'Hak Akses sebagai Super Admin',
                'is_active' => true,
                'created_at' => '2023-11-25 23:08:00',
                'updated_at' => '2023-11-25 23:08:00',
            ],
            [
                'id' => 2,
                'name' => 'Siswa - Siswi',
                'desc' => 'Hak Akses sebagai Siswa - Siswi',
                'is_active' => true,
                'created_at' => '2023-11-25 23:08:00',
                'updated_at' => '2023-11-25 23:08:00',
            ],
            [
                'id' => 3,
                'name' => 'Guru BK',
                'desc' => 'Hak Akses sebagai Guru BK',
                'is_active' => true,
                'created_at' => '2023-11-25 23:08:00',
                'updated_at' => '2023-11-25 23:08:00',
            ],
            [
                'id' => 4,
                'name' => 'Admin Raport',
                'desc' => 'Hak Akses sebagai Admin raport',
                'is_active' => true,
                'created_at' => '2023-11-25 23:08:00',
                'updated_at' => '2023-11-25 23:08:00',
            ],
            [
                'id' => 5,
                'name' => 'Bagian Kurikulum',
                'desc' => 'Hak Akses sebagai Bagian Kurikulum',
                'is_active' => true,
                'created_at' => '2023-11-25 23:08:00',
                'updated_at' => '2023-11-25 23:08:00',
            ],
            [
                'id' => 6,
                'name' => 'Bagian Tata Usaha',
                'desc' => 'Hak Akses sebagai Bagian Tata Usaha',
                'is_active' => true,
                'created_at' => '2023-11-25 23:08:00',
                'updated_at' => '2023-11-25 23:08:00',
            ],
            [
                'id' => 7,
                'name' => 'Guru Agama',
                'desc' => 'Hak Akses sebagai Guru Agama',
                'is_active' => true,
                'created_at' => '2023-11-25 23:08:00',
                'updated_at' => '2023-11-25 23:08:00',
            ],
            [
                'id' => 8,
                'name' => 'Wali Kelas',
                'desc' => 'Hak Akses sebagai Wali Kelas',
                'is_active' => true,
                'created_at' => '2023-11-25 23:08:00',
                'updated_at' => '2023-11-25 23:08:00',
            ]
        ];

        Role::insert($data);
    }
}
