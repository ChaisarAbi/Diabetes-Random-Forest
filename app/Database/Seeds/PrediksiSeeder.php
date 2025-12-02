<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PrediksiSeeder extends Seeder
{
    public function run()
    {
        // First, get all patient IDs
        $pasienIds = $this->db->table('pasien')->select('id')->get()->getResultArray();
        if (empty($pasienIds)) {
            // If no patients, run PasienSeeder first
            $this->call('PasienSeeder');
            $pasienIds = $this->db->table('pasien')->select('id')->get()->getResultArray();
        }

        $faker = \Faker\Factory::create('id_ID');
        $data = [];

        // Create predictions for the last 6 months
        for ($i = 0; $i < 50; $i++) {
            $randomPasien = $faker->randomElement($pasienIds);
            $hasil = $faker->numberBetween(0, 1); // 0 = Tidak Diabetes, 1 = Diabetes
            
            // Generate realistic diabetes prediction data based on PIMA Indians dataset
            $pregnancies = $faker->numberBetween(0, 15);
            $glucose = $hasil == 1 ? $faker->numberBetween(140, 200) : $faker->numberBetween(70, 139);
            $bloodPressure = $faker->numberBetween(60, 110);
            $skinThickness = $faker->numberBetween(10, 50);
            $insulin = $faker->numberBetween(0, 300);
            $bmi = $hasil == 1 ? $faker->randomFloat(2, 30, 45) : $faker->randomFloat(2, 18, 29);
            $dpf = $faker->randomFloat(3, 0.1, 2.0);
            $age = $faker->numberBetween(21, 70);
            
            // Random date within last 6 months
            $daysAgo = $faker->numberBetween(0, 180);
            $createdAt = Time::now()->subDays($daysAgo);

            $data[] = [
                'id_pasien' => $randomPasien['id'],
                'pregnancies' => $pregnancies,
                'glucose' => $glucose,
                'blood_pressure' => $bloodPressure,
                'skin_thickness' => $skinThickness,
                'insulin' => $insulin,
                'bmi' => $bmi,
                'dpf' => $dpf,
                'age' => $age,
                'hasil' => $hasil,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        $this->db->table('prediksi')->insertBatch($data);
    }
}
