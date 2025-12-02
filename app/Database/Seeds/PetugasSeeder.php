<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PetugasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama' => 'Admin Utama',
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'status_aktif' => 1,
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'nama' => 'Petugas 1',
                'username' => 'petugas1',
                'password' => password_hash('petugas123', PASSWORD_DEFAULT),
                'role' => 'petugas',
                'status_aktif' => 1,
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
            [
                'nama' => 'Petugas 2',
                'username' => 'petugas2',
                'password' => password_hash('petugas123', PASSWORD_DEFAULT),
                'role' => 'petugas',
                'status_aktif' => 1,
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ],
        ];

        $this->db->table('petugas')->insertBatch($data);
    }
}
