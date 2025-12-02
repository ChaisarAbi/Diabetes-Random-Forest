<?php

namespace App\Controllers;

use App\Models\PrediksiModel;
use App\Models\PasienModel;

class Prediksi extends BaseController
{
    protected $prediksiModel;
    protected $pasienModel;
    protected $validation;

    public function __construct()
    {
        $this->prediksiModel = new PrediksiModel();
        $this->pasienModel = new PasienModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'Prediksi Diabetes',
            'prediksi' => $this->prediksiModel->getPrediksiWithPasien(),
        ];

        return view('prediksi/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Form Prediksi Diabetes',
            'pasien' => $this->pasienModel->findAll(),
            'validation' => $this->validation,
        ];

        return view('prediksi/create', $data);
    }

    public function store()
    {
        // Validation rules for prediction form
        $rules = [
            'id_pasien' => 'required|numeric',
            'pregnancies' => 'required|numeric|greater_than_equal_to[0]',
            'glucose' => 'required|numeric|greater_than[0]',
            'blood_pressure' => 'required|numeric|greater_than[0]',
            'skin_thickness' => 'required|numeric|greater_than[0]',
            'insulin' => 'required|numeric|greater_than_equal_to[0]',
            'bmi' => 'required|numeric|greater_than[0]',
            'dpf' => 'required|numeric|greater_than[0]',
            'age' => 'required|numeric|greater_than[0]|less_than[150]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data for Python prediction
        $predictionData = [
            'pregnancies' => (float)$this->request->getPost('pregnancies'),
            'glucose' => (float)$this->request->getPost('glucose'),
            'blood_pressure' => (float)$this->request->getPost('blood_pressure'),
            'skin_thickness' => (float)$this->request->getPost('skin_thickness'),
            'insulin' => (float)$this->request->getPost('insulin'),
            'bmi' => (float)$this->request->getPost('bmi'),
            'dpf' => (float)$this->request->getPost('dpf'),
            'age' => (float)$this->request->getPost('age'),
        ];

        // Call Python script for prediction
        $hasil = $this->callPythonPrediction($predictionData);

        // Save prediction to database
        $data = [
            'id_pasien' => $this->request->getPost('id_pasien'),
            'pregnancies' => $predictionData['pregnancies'],
            'glucose' => $predictionData['glucose'],
            'blood_pressure' => $predictionData['blood_pressure'],
            'skin_thickness' => $predictionData['skin_thickness'],
            'insulin' => $predictionData['insulin'],
            'bmi' => $predictionData['bmi'],
            'dpf' => $predictionData['dpf'],
            'age' => $predictionData['age'],
            'hasil' => $hasil,
        ];

        if ($this->prediksiModel->save($data)) {
            $status = $hasil == 1 ? 'Diabetes' : 'Tidak Diabetes';
            return redirect()->to('/prediksi')->with('success', "Prediksi berhasil disimpan. Hasil: $status");
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan prediksi.');
        }
    }

    public function detail($id)
    {
        $prediksi = $this->prediksiModel->getPrediksiByIdWithPasien($id);
        
        if (!$prediksi) {
            return redirect()->to('/prediksi')->with('error', 'Data prediksi tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Prediksi',
            'prediksi' => $prediksi,
        ];

        return view('prediksi/detail', $data);
    }

    private function callPythonPrediction($data)
    {
        // Convert data to JSON
        $jsonData = json_encode($data);
        
        // Escape JSON for command line
        $escapedJson = escapeshellarg($jsonData);
        
        // Path to Python script
        $pythonScript = APPPATH . 'Python/predict.py';
        
        // Execute Python script
        $command = "python " . escapeshellarg($pythonScript) . " " . $escapedJson;
        $output = shell_exec($command);
        
        // Clean and validate output
        $output = trim($output);
        
        // If Python script fails, use fallback logic
        if ($output === null || !is_numeric($output)) {
            // Fallback: Simple rule-based prediction based on glucose level
            // This is just for demonstration if Python fails
            if ($data['glucose'] > 140 || $data['age'] > 50 && $data['bmi'] > 30) {
                return 1; // Diabetes
            } else {
                return 0; // Not Diabetes
            }
        }
        
        return (int)$output;
    }
}
