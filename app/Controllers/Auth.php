<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PetugasModel;

class Auth extends BaseController
{
    protected $petugasModel;
    
    public function __construct()
    {
        $this->petugasModel = new PetugasModel();
        helper(['form', 'url']);
    }
    
    public function login()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        $data = [
            'title' => 'Login - Sistem Prediksi Diabetes',
            'validation' => \Config\Services::validation(),
        ];
        
        return view('auth/login', $data);
    }
    
    public function processLogin()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'username' => 'required',
            'password' => 'required',
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        $petugas = $this->petugasModel->where('username', $username)
                                      ->where('status_aktif', 1)
                                      ->first();
        
        if (!$petugas) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah!');
        }
        
        if (!password_verify($password, $petugas['password'])) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah!');
        }
        
        // Set session data
        $sessionData = [
            'id_petugas' => $petugas['id'],
            'nama' => $petugas['nama'],
            'username' => $petugas['username'],
            'role' => $petugas['role'],
            'logged_in' => true,
        ];
        
        session()->set($sessionData);
        
        // Redirect based on role
        if ($petugas['role'] === 'admin') {
            return redirect()->to('/dashboard')->with('success', 'Selamat datang, Admin!');
        } else {
            return redirect()->to('/dashboard')->with('success', 'Selamat datang, Petugas!');
        }
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Anda telah logout.');
    }
}
