<?php

namespace App\Controllers;

use App\Models\PasienModel;

class Pasien extends BaseController
{
    protected $pasienModel;
    protected $validation;

    public function __construct()
    {
        $this->pasienModel = new PasienModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Pasien',
            'pasien' => $this->pasienModel->findAll(),
        ];

        return view('pasien/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Pasien',
            'validation' => $this->validation,
        ];

        return view('pasien/create', $data);
    }

    public function store()
    {
        // Validation rules
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'umur' => 'required|numeric|greater_than[0]|less_than[150]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'berat' => 'required|numeric|greater_than[0]',
            'tinggi' => 'required|numeric|greater_than[0]',
            'alamat' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'umur' => $this->request->getPost('umur'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'berat' => $this->request->getPost('berat'),
            'tinggi' => $this->request->getPost('tinggi'),
            'alamat' => $this->request->getPost('alamat'),
        ];

        if ($this->pasienModel->save($data)) {
            return redirect()->to('/pasien')->with('success', 'Data pasien berhasil ditambahkan.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data pasien.');
        }
    }

    public function edit($id)
    {
        $pasien = $this->pasienModel->find($id);
        
        if (!$pasien) {
            return redirect()->to('/pasien')->with('error', 'Data pasien tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Pasien',
            'pasien' => $pasien,
            'validation' => $this->validation,
        ];

        return view('pasien/edit', $data);
    }

    public function update($id)
    {
        // Check if patient exists
        $pasien = $this->pasienModel->find($id);
        if (!$pasien) {
            return redirect()->to('/pasien')->with('error', 'Data pasien tidak ditemukan.');
        }

        // Validation rules
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'umur' => 'required|numeric|greater_than[0]|less_than[150]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'berat' => 'required|numeric|greater_than[0]',
            'tinggi' => 'required|numeric|greater_than[0]',
            'alamat' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'umur' => $this->request->getPost('umur'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'berat' => $this->request->getPost('berat'),
            'tinggi' => $this->request->getPost('tinggi'),
            'alamat' => $this->request->getPost('alamat'),
        ];

        if ($this->pasienModel->update($id, $data)) {
            return redirect()->to('/pasien')->with('success', 'Data pasien berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data pasien.');
        }
    }

    public function delete($id)
    {
        $pasien = $this->pasienModel->find($id);
        
        if (!$pasien) {
            return redirect()->to('/pasien')->with('error', 'Data pasien tidak ditemukan.');
        }

        if ($this->pasienModel->delete($id)) {
            return redirect()->to('/pasien')->with('success', 'Data pasien berhasil dihapus.');
        } else {
            return redirect()->to('/pasien')->with('error', 'Gagal menghapus data pasien.');
        }
    }
}
