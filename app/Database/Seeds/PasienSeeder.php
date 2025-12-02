<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PasienSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        $data = [];

        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'nama' => $faker->name,
                'umur' => $faker->numberBetween(20, 70),
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'berat' => $faker->randomFloat(1, 40, 120),
                'tinggi' => $faker->randomFloat(1, 140, 190),
                'alamat' => $faker->address,
                'created_at' => Time::now(),
                'updated_at' => Time::now(),
            ];
        }

        $this->db->table('pasien')->insertBatch($data);
    }
}
