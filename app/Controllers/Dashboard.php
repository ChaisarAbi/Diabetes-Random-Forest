<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PasienModel;
use App\Models\PrediksiModel;
use App\Models\PetugasModel;

class Dashboard extends BaseController
{
    protected $pasienModel;
    protected $prediksiModel;
    protected $petugasModel;
    
    public function __construct()
    {
        $this->pasienModel = new PasienModel();
        $this->prediksiModel = new PrediksiModel();
        $this->petugasModel = new PetugasModel();
        helper(['form', 'url']);
    }
    
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        $data = [
            'title' => 'Dashboard - Sistem Prediksi Diabetes',
            'total_pasien' => $this->pasienModel->countAll(),
            'total_prediksi' => $this->prediksiModel->countAll(),
            'total_petugas' => $this->petugasModel->countAll(),
            'total_positif' => $this->prediksiModel->where('hasil', 1)->countAllResults(),
            'recent_predictions' => $this->prediksiModel
                ->select('prediksi.*, pasien.nama as nama_pasien')
                ->join('pasien', 'pasien.id = prediksi.id_pasien')
                ->orderBy('prediksi.created_at', 'DESC')
                ->limit(5)
                ->findAll(),
            'monthly_stats' => $this->getMonthlyStats(),
        ];
        
        return view('dashboard/index', $data);
    }
    
    private function getMonthlyStats()
    {
        $db = \Config\Database::connect();
        $currentYear = date('Y');
        
        $query = $db->query("
            SELECT 
                MONTH(created_at) as bulan,
                COUNT(*) as total,
                SUM(CASE WHEN hasil = 1 THEN 1 ELSE 0 END) as positif
            FROM prediksi
            WHERE YEAR(created_at) = ?
            GROUP BY MONTH(created_at)
            ORDER BY bulan
        ", [$currentYear]);
        
        $results = $query->getResultArray();
        
        $months = [];
        $totals = [];
        $positifs = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $found = false;
            foreach ($results as $row) {
                if ($row['bulan'] == $i) {
                    $months[] = date('M', mktime(0, 0, 0, $i, 1));
                    $totals[] = (int)$row['total'];
                    $positifs[] = (int)$row['positif'];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $months[] = date('M', mktime(0, 0, 0, $i, 1));
                $totals[] = 0;
                $positifs[] = 0;
            }
        }
        
        return [
            'months' => $months,
            'totals' => $totals,
            'positifs' => $positifs,
        ];
    }
}
