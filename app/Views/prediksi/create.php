<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Buat Prediksi Diabetes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('prediksi') ?>">Prediksi Diabetes</a></li>
                    <li class="breadcrumb-item active">Buat Prediksi</li>
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
                        <h3 class="card-title">Form Prediksi Diabetes</h3>
                    </div>
                    <form action="<?= base_url('prediksi/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <?php if (isset($validation)): ?>
                                <div class="alert alert-danger">
                                    <?= $validation->listErrors() ?>
                                </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_pasien">Pilih Pasien *</label>
                                        <select class="form-control" id="id_pasien" name="id_pasien" required>
                                            <option value="">Pilih Pasien</option>
                                            <?php foreach ($pasien as $p): ?>
                                                <option value="<?= $p['id'] ?>" <?= old('id_pasien') == $p['id'] ? 'selected' : '' ?>>
                                                    <?= esc($p['nama']) ?> (<?= $p['umur'] ?> tahun, <?= $p['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">Data Kesehatan Pasien</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pregnancies">Pregnancies *</label>
                                        <input type="number" class="form-control" id="pregnancies" name="pregnancies" 
                                               value="<?= old('pregnancies') ?>" required min="0" max="20" 
                                               placeholder="Jumlah kehamilan">
                                        <small class="text-muted">Jumlah kali hamil</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="glucose">Glucose *</label>
                                        <input type="number" step="0.1" class="form-control" id="glucose" name="glucose" 
                                               value="<?= old('glucose') ?>" required min="0" max="300" 
                                               placeholder="Konsentrasi glukosa">
                                        <small class="text-muted">mg/dL</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="blood_pressure">Blood Pressure *</label>
                                        <input type="number" step="0.1" class="form-control" id="blood_pressure" name="blood_pressure" 
                                               value="<?= old('blood_pressure') ?>" required min="0" max="200" 
                                               placeholder="Tekanan darah">
                                        <small class="text-muted">mm Hg</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="skin_thickness">Skin Thickness *</label>
                                        <input type="number" step="0.1" class="form-control" id="skin_thickness" name="skin_thickness" 
                                               value="<?= old('skin_thickness') ?>" required min="0" max="100" 
                                               placeholder="Ketebalan kulit">
                                        <small class="text-muted">mm</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="insulin">Insulin *</label>
                                        <input type="number" step="0.1" class="form-control" id="insulin" name="insulin" 
                                               value="<?= old('insulin') ?>" required min="0" max="900" 
                                               placeholder="Insulin">
                                        <small class="text-muted">mu U/ml</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bmi">BMI *</label>
                                        <input type="number" step="0.01" class="form-control" id="bmi" name="bmi" 
                                               value="<?= old('bmi') ?>" required min="0" max="100" 
                                               placeholder="Body Mass Index">
                                        <small class="text-muted">kg/m²</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="dpf">Diabetes Pedigree Function *</label>
                                        <input type="number" step="0.001" class="form-control" id="dpf" name="dpf" 
                                               value="<?= old('dpf') ?>" required min="0" max="3" 
                                               placeholder="Fungsi silsilah diabetes">
                                        <small class="text-muted">0.000 - 2.000</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="age">Age *</label>
                                        <input type="number" class="form-control" id="age" name="age" 
                                               value="<?= old('age') ?>" required min="1" max="120" 
                                               placeholder="Usia">
                                        <small class="text-muted">Tahun</small>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3">
                                <h5><i class="icon fas fa-info"></i> Informasi</h5>
                                <p>Sistem akan memprediksi diabetes menggunakan model <strong>Random Forest</strong> berdasarkan dataset PIMA Indians Diabetes.</p>
                                <p>Hasil prediksi: <strong>0 = Tidak Diabetes</strong>, <strong>1 = Diabetes</strong></p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-calculator"></i> Prediksi Sekarang
                            </button>
                            <a href="<?= base_url('prediksi') ?>" class="btn btn-default">
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
