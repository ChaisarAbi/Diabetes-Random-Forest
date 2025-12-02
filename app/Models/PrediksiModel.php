<?php

namespace App\Models;

use CodeIgniter\Model;

class PrediksiModel extends Model
{
    protected $table            = 'prediksi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_pasien', 'pregnancies', 'glucose', 'blood_pressure', 'skin_thickness', 'insulin', 'bmi', 'dpf', 'age', 'hasil', 'created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id_pasien' => 'required|numeric',
        'pregnancies' => 'required|numeric|greater_than_equal_to[0]',
        'glucose' => 'required|numeric|greater_than[0]',
        'blood_pressure' => 'required|numeric|greater_than[0]',
        'skin_thickness' => 'required|numeric|greater_than[0]',
        'insulin' => 'required|numeric|greater_than_equal_to[0]',
        'bmi' => 'required|numeric|greater_than[0]',
        'dpf' => 'required|numeric|greater_than_equal_to[0]',
        'age' => 'required|numeric|greater_than[0]',
        'hasil' => 'required|in_list[0,1]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get prediksi with pasien data
     */
    public function getPrediksiWithPasien()
    {
        return $this->select('prediksi.*, pasien.nama as nama_pasien')
                    ->join('pasien', 'pasien.id = prediksi.id_pasien')
                    ->orderBy('prediksi.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get prediksi by id with pasien data
     */
    public function getPrediksiByIdWithPasien($id)
    {
        return $this->select('prediksi.*, pasien.nama as nama_pasien, pasien.umur, pasien.jenis_kelamin')
                    ->join('pasien', 'pasien.id = prediksi.id_pasien')
                    ->where('prediksi.id', $id)
                    ->first();
    }

    /**
     * Get statistics for dashboard
     */
    public function getDashboardStats()
    {
        $total = $this->countAllResults();
        $positif = $this->where('hasil', 1)->countAllResults();
        $negatif = $this->where('hasil', 0)->countAllResults();

        return [
            'total' => $total,
            'positif' => $positif,
            'negatif' => $negatif,
            'persentase_positif' => $total > 0 ? round(($positif / $total) * 100, 2) : 0,
        ];
    }
}
