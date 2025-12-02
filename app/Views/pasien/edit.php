<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Pasien</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('pasien') ?>">Data Pasien</a></li>
                    <li class="breadcrumb-item active">Edit Pasien</li>
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
                        <h3 class="card-title">Form Edit Pasien</h3>
                    </div>
                    <form action="<?= base_url('pasien/update/' . $pasien['id']) ?>" method="post">
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
                                        <label for="nama">Nama Pasien *</label>
                                        <input type="text" class="form-control" id="nama" name="nama" 
                                               value="<?= old('nama', $pasien['nama']) ?>" required placeholder="Masukkan nama pasien">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="umur">Umur *</label>
                                        <input type="number" class="form-control" id="umur" name="umur" 
                                               value="<?= old('umur', $pasien['umur']) ?>" required min="1" max="120" placeholder="Tahun">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin *</label>
                                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L" <?= (old('jenis_kelamin', $pasien['jenis_kelamin']) == 'L') ? 'selected' : '' ?>>Laki-laki</option>
                                            <option value="P" <?= (old('jenis_kelamin', $pasien['jenis_kelamin']) == 'P') ? 'selected' : '' ?>>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="berat">Berat Badan (kg) *</label>
                                        <input type="number" step="0.1" class="form-control" id="berat" name="berat" 
                                               value="<?= old('berat', $pasien['berat']) ?>" required min="1" max="300" placeholder="Contoh: 65.5">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tinggi">Tinggi Badan (cm) *</label>
                                        <input type="number" step="0.1" class="form-control" id="tinggi" name="tinggi" 
                                               value="<?= old('tinggi', $pasien['tinggi']) ?>" required min="30" max="300" placeholder="Contoh: 170.5">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <textarea class="form-control" id="alamat" name="alamat" rows="2" 
                                                  placeholder="Masukkan alamat pasien (opsional)"><?= old('alamat', $pasien['alamat'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <a href="<?= base_url('pasien') ?>" class="btn btn-default">
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
