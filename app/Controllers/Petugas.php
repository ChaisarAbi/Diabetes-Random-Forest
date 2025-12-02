<?php

namespace App\Controllers;

use App\Models\PetugasModel;

class Petugas extends BaseController
{
    protected $petugasModel;

    public function __construct()
    {
        $this->petugasModel = new PetugasModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }

        $data = [
            'title' => 'Data Petugas',
            'petugas' => $this->petugasModel->findAll(),
        ];

        return view('petugas/index', $data);
    }

    public function create()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }

        $data = [
            'title' => 'Tambah Petugas',
            'validation' => \Config\Services::validation(),
        ];

        return view('petugas/create', $data);
    }

    public function store()
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }

        // Validation rules
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[50]|is_unique[petugas.username]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[admin,petugas]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'status_aktif' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->petugasModel->insert($data);

        return redirect()->to('/petugas')->with('success', 'Petugas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }

        $petugas = $this->petugasModel->find($id);
        if (!$petugas) {
            return redirect()->to('/petugas')->with('error', 'Petugas tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Petugas',
            'petugas' => $petugas,
            'validation' => \Config\Services::validation(),
        ];

        return view('petugas/edit', $data);
    }

    public function update($id)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }

        $petugas = $this->petugasModel->find($id);
        if (!$petugas) {
            return redirect()->to('/petugas')->with('error', 'Petugas tidak ditemukan.');
        }

        // Validation rules
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'username' => "required|min_length[3]|max_length[50]|is_unique[petugas.username,id,$id]",
            'role' => 'required|in_list[admin,petugas]',
        ];

        // Add password rule only if password is provided
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'role' => $this->request->getPost('role'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Update password only if provided
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->petugasModel->update($id, $data);

        return redirect()->to('/petugas')->with('success', 'Petugas berhasil diperbarui.');
    }

    public function delete($id)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }

        // Prevent deleting self
        if ($id == session()->get('id')) {
            return redirect()->to('/petugas')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $petugas = $this->petugasModel->find($id);
        if (!$petugas) {
            return redirect()->to('/petugas')->with('error', 'Petugas tidak ditemukan.');
        }

        $this->petugasModel->delete($id);

        return redirect()->to('/petugas')->with('success', 'Petugas berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        // Check if user is admin
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }

        $petugas = $this->petugasModel->find($id);
        if (!$petugas) {
            return redirect()->to('/petugas')->with('error', 'Petugas tidak ditemukan.');
        }

        $newStatus = $petugas['status_aktif'] ? 0 : 1;
        $this->petugasModel->update($id, ['status_aktif' => $newStatus]);

        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->to('/petugas')->with('success', "Petugas berhasil $statusText.");
    }
}
