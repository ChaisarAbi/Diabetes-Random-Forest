<?php

namespace App\Controllers;

use App\Models\PrediksiModel;
use App\Models\PasienModel;

class Laporan extends BaseController
{
    protected $prediksiModel;
    protected $pasienModel;

    public function __construct()
    {
        $this->prediksiModel = new PrediksiModel();
        $this->pasienModel = new PasienModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data = [
            'title' => 'Laporan Hasil Prediksi',
        ];

        return view('laporan/index', $data);
    }

    public function hasil()
    {
        // Get filter parameters
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $idPasien = $this->request->getGet('id_pasien');
        $hasil = $this->request->getGet('hasil');

        // Build query
        $builder = $this->prediksiModel->select('prediksi.*, pasien.nama as nama_pasien')
            ->join('pasien', 'pasien.id = prediksi.id_pasien');

        // Apply filters
        if ($startDate) {
            $builder->where('DATE(prediksi.created_at) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(prediksi.created_at) <=', $endDate);
        }
        if ($idPasien) {
            $builder->where('prediksi.id_pasien', $idPasien);
        }
        if ($hasil !== null && $hasil !== '') {
            $builder->where('prediksi.hasil', $hasil);
        }

        $prediksi = $builder->orderBy('prediksi.created_at', 'DESC')->findAll();

        // Get statistics
        $total = count($prediksi);
        $positif = array_filter($prediksi, function($item) {
            return $item['hasil'] == 1;
        });
        $negatif = array_filter($prediksi, function($item) {
            return $item['hasil'] == 0;
        });

        $data = [
            'title' => 'Hasil Prediksi',
            'prediksi' => $prediksi,
            'pasien' => $this->pasienModel->findAll(),
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'id_pasien' => $idPasien,
                'hasil' => $hasil,
            ],
            'statistics' => [
                'total' => $total,
                'positif' => count($positif),
                'negatif' => count($negatif),
                'persentase_positif' => $total > 0 ? round((count($positif) / $total) * 100, 2) : 0,
            ],
        ];

        return view('laporan/hasil', $data);
    }

    public function bulanan()
    {
        // Get current year and month
        $year = $this->request->getGet('year') ?: date('Y');
        $month = $this->request->getGet('month') ?: date('m');

        // Get monthly statistics
        $monthlyData = $this->getMonthlyStatistics($year, $month);

        $data = [
            'title' => 'Laporan Bulanan',
            'year' => $year,
            'month' => $month,
            'monthly_data' => $monthlyData,
            'years' => $this->getAvailableYears(),
        ];

        return view('laporan/bulanan', $data);
    }

    private function getMonthlyStatistics($year, $month)
    {
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        // Get all predictions for the month
        $prediksi = $this->prediksiModel->select('prediksi.*, pasien.nama as nama_pasien')
            ->join('pasien', 'pasien.id = prediksi.id_pasien')
            ->where("DATE(prediksi.created_at) BETWEEN '$startDate' AND '$endDate'")
            ->orderBy('prediksi.created_at', 'ASC')
            ->findAll();

        // Group by day
        $dailyStats = [];
        foreach ($prediksi as $p) {
            $day = date('d', strtotime($p['created_at']));
            if (!isset($dailyStats[$day])) {
                $dailyStats[$day] = [
                    'total' => 0,
                    'positif' => 0,
                    'negatif' => 0,
                ];
            }
            $dailyStats[$day]['total']++;
            if ($p['hasil'] == 1) {
                $dailyStats[$day]['positif']++;
            } else {
                $dailyStats[$day]['negatif']++;
            }
        }

        // Calculate monthly totals
        $monthlyTotal = count($prediksi);
        $monthlyPositif = array_filter($prediksi, function($item) {
            return $item['hasil'] == 1;
        });
        $monthlyNegatif = array_filter($prediksi, function($item) {
            return $item['hasil'] == 0;
        });

        return [
            'daily_stats' => $dailyStats,
            'monthly_total' => $monthlyTotal,
            'monthly_positif' => count($monthlyPositif),
            'monthly_negatif' => count($monthlyNegatif),
            'persentase_positif' => $monthlyTotal > 0 ? round((count($monthlyPositif) / $monthlyTotal) * 100, 2) : 0,
            'prediksi' => $prediksi,
        ];
    }

    private function getAvailableYears()
    {
        $years = [];
        $currentYear = date('Y');
        for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
            $years[] = $i;
        }
        return $years;
    }

    public function exportPdf()
    {
        // Get filter parameters
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $idPasien = $this->request->getGet('id_pasien');
        $hasil = $this->request->getGet('hasil');

        // Build query
        $builder = $this->prediksiModel->select('prediksi.*, pasien.nama as nama_pasien')
            ->join('pasien', 'pasien.id = prediksi.id_pasien');

        // Apply filters
        if ($startDate) {
            $builder->where('DATE(prediksi.created_at) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(prediksi.created_at) <=', $endDate);
        }
        if ($idPasien) {
            $builder->where('prediksi.id_pasien', $idPasien);
        }
        if ($hasil !== null && $hasil !== '') {
            $builder->where('prediksi.hasil', $hasil);
        }

        $prediksi = $builder->orderBy('prediksi.created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Laporan Hasil Prediksi Diabetes',
            'prediksi' => $prediksi,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'id_pasien' => $idPasien,
                'hasil' => $hasil,
            ],
            'export_date' => date('d-m-Y H:i:s'),
        ];

        // For now, just return view. In production, you would use a PDF library like Dompdf
        return view('laporan/export_pdf', $data);
    }

    public function exportExcel()
    {
        // Get filter parameters
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $idPasien = $this->request->getGet('id_pasien');
        $hasil = $this->request->getGet('hasil');

        // Build query
        $builder = $this->prediksiModel->select('prediksi.*, pasien.nama as nama_pasien')
            ->join('pasien', 'pasien.id = prediksi.id_pasien');

        // Apply filters
        if ($startDate) {
            $builder->where('DATE(prediksi.created_at) >=', $startDate);
        }
        if ($endDate) {
            $builder->where('DATE(prediksi.created_at) <=', $endDate);
        }
        if ($idPasien) {
            $builder->where('prediksi.id_pasien', $idPasien);
        }
        if ($hasil !== null && $hasil !== '') {
            $builder->where('prediksi.hasil', $hasil);
        }

        $prediksi = $builder->orderBy('prediksi.created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Laporan Hasil Prediksi Diabetes',
            'prediksi' => $prediksi,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'id_pasien' => $idPasien,
                'hasil' => $hasil,
            ],
            'export_date' => date('d-m-Y H:i:s'),
        ];

        // For now, just return view. In production, you would use a spreadsheet library like PhpSpreadsheet
        return view('laporan/export_excel', $data);
    }
}
