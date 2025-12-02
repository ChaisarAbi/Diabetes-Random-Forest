<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrediksiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_pasien' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'pregnancies' => [
                'type' => 'INT',
                'constraint' => 2,
            ],
            'glucose' => [
                'type' => 'FLOAT',
            ],
            'blood_pressure' => [
                'type' => 'FLOAT',
            ],
            'skin_thickness' => [
                'type' => 'FLOAT',
            ],
            'insulin' => [
                'type' => 'FLOAT',
            ],
            'bmi' => [
                'type' => 'FLOAT',
            ],
            'dpf' => [
                'type' => 'FLOAT',
                'comment' => 'Diabetes Pedigree Function',
            ],
            'age' => [
                'type' => 'INT',
                'constraint' => 3,
            ],
            'hasil' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '0 = Tidak Diabetes, 1 = Diabetes',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_pasien', 'pasien', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('prediksi');
    }

    public function down()
    {
        $this->forge->dropTable('prediksi');
    }
}
