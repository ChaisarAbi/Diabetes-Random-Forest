<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Petugas/Admin</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('petugas') ?>">Data Petugas</a></li>
                    <li class="breadcrumb-item active">Edit Petugas</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Form Edit Petugas/Admin</h3>
                    </div>
                    <form action="<?= base_url('petugas/update/' . $petugas['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="PUT">
                        <div class="card-body">
                            <?php if (isset($validation)): ?>
                                <div class="alert alert-danger">
                                    <?= $validation->listErrors() ?>
                                </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Nama Lengkap *</label>
                                        <input type="text" class="form-control" id="nama" name="nama" 
                                               value="<?= old('nama', $petugas['nama']) ?>" required placeholder="Masukkan nama lengkap">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">Username *</label>
                                        <input type="text" class="form-control" id="username" name="username" 
                                               value="<?= old('username', $petugas['username']) ?>" required placeholder="Masukkan username">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Kosongkan jika tidak ingin mengubah">
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Konfirmasi Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" 
                                               name="password_confirmation" placeholder="Konfirmasi password">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Role *</label>
                                        <select class="form-control" id="role" name="role" required>
                                            <option value="">Pilih Role</option>
                                            <option value="admin" <?= (old('role', $petugas['role']) == 'admin') ? 'selected' : '' ?>>Admin</option>
                                            <option value="petugas" <?= (old('role', $petugas['role']) == 'petugas') ? 'selected' : '' ?>>Petugas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status_aktif">Status</label>
                                        <select class="form-control" id="status_aktif" name="status_aktif">
                                            <option value="1" <?= (old('status_aktif', $petugas['status_aktif']) == 1) ? 'selected' : '' ?>>Aktif</option>
                                            <option value="0" <?= (old('status_aktif', $petugas['status_aktif']) == 0) ? 'selected' : '' ?>>Non-Aktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3">
                                <h5><i class="icon fas fa-info"></i> Informasi</h5>
                                <p><strong>Admin:</strong> Memiliki akses penuh ke semua fitur sistem.</p>
                                <p><strong>Petugas:</strong> Hanya dapat mengelola data pasien dan prediksi, tidak dapat mengelola data petugas.</p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <a href="<?= base_url('petugas') ?>" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
