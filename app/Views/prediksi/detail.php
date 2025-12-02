<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Detail Prediksi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('prediksi') ?>">Prediksi Diabetes</a></li>
                    <li class="breadcrumb-item active">Detail Prediksi</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Hasil Prediksi Diabetes</h3>
                        <div class="card-tools">
                            <a href="<?= base_url('prediksi') ?>" class="btn btn-sm btn-default">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-lg">Informasi Pasien</span>
                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <strong>Nama Pasien:</strong><br>
                                                <?= esc($prediksi['nama_pasien']) ?>
                                            </div>
                                            <div class="col-3">
                                                <strong>Umur:</strong><br>
                                                <?= esc($prediksi['umur']) ?> tahun
                                            </div>
                                            <div class="col-3">
                                                <strong>Jenis Kelamin:</strong><br>
                                                <?= $prediksi['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box <?= $prediksi['hasil'] == 1 ? 'bg-danger' : 'bg-success' ?>">
                                    <div class="info-box-content text-center">
                                        <span class="info-box-text text-lg text-white">Hasil Prediksi</span>
                                        <span class="info-box-number text-white" style="font-size: 2.5rem;">
                                            <?= $prediksi['hasil'] == 1 ? 'DIABETES' : 'TIDAK DIABETES' ?>
                                        </span>
                                        <span class="text-white">
                                            <?= $prediksi['hasil'] == 1 ? 'Pasien terindikasi diabetes' : 'Pasien tidak terindikasi diabetes' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h4>Data Kesehatan Pasien</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Nilai</th>
                                                <th>Satuan</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Pregnancies</strong></td>
                                                <td><?= esc($prediksi['pregnancies']) ?></td>
                                                <td>kali</td>
                                                <td>Jumlah kehamilan</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Glucose</strong></td>
                                                <td><?= esc($prediksi['glucose']) ?></td>
                                                <td>mg/dL</td>
                                                <td>Konsentrasi glukosa plasma 2 jam dalam tes toleransi glukosa oral</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Blood Pressure</strong></td>
                                                <td><?= esc($prediksi['blood_pressure']) ?></td>
                                                <td>mm Hg</td>
                                                <td>Tekanan darah diastolik</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Skin Thickness</strong></td>
                                                <td><?= esc($prediksi['skin_thickness']) ?></td>
                                                <td>mm</td>
                                                <td>Ketebalan lipatan kulit trisep</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Insulin</strong></td>
                                                <td><?= esc($prediksi['insulin']) ?></td>
                                                <td>mu U/ml</td>
                                                <td>Insulin serum 2 jam</td>
                                            </tr>
                                            <tr>
                                                <td><strong>BMI</strong></td>
                                                <td><?= esc($prediksi['bmi']) ?></td>
                                                <td>kg/m²</td>
                                                <td>Indeks massa tubuh</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Diabetes Pedigree Function</strong></td>
                                                <td><?= esc($prediksi['dpf']) ?></td>
                                                <td>-</td>
                                                <td>Fungsi yang menilai riwayat diabetes dalam keluarga</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Age</strong></td>
                                                <td><?= esc($prediksi['age']) ?></td>
                                                <td>tahun</td>
                                                <td>Usia pasien</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card <?= $prediksi['hasil'] == 1 ? 'border-danger' : 'border-success' ?>">
                                    <div class="card-header <?= $prediksi['hasil'] == 1 ? 'bg-danger text-white' : 'bg-success text-white' ?>">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-info-circle"></i> Interpretasi Hasil
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($prediksi['hasil'] == 1): ?>
                                            <div class="alert alert-danger">
                                                <h5><i class="fas fa-exclamation-triangle"></i> <strong>Hasil: DIABETES</strong></h5>
                                                <p>Berdasarkan data yang diinputkan, model Random Forest memprediksi bahwa pasien <strong>terindikasi diabetes</strong>.</p>
                                                <p><strong>Rekomendasi:</strong></p>
                                                <ul>
                                                    <li>Segera konsultasi dengan dokter spesialis penyakit dalam</li>
                                                    <li>Lakukan pemeriksaan HbA1c untuk konfirmasi</li>
                                                    <li>Mulai pola makan sehat dengan kontrol karbohidrat</li>
                                                    <li>Rutin berolahraga minimal 30 menit per hari</li>
                                                    <li>Monitor kadar gula darah secara berkala</li>
                                                </ul>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-success">
                                                <h5><i class="fas fa-check-circle"></i> <strong>Hasil: TIDAK DIABETES</strong></h5>
                                                <p>Berdasarkan data yang diinputkan, model Random Forest memprediksi bahwa pasien <strong>tidak terindikasi diabetes</strong>.</p>
                                                <p><strong>Saran Pencegahan:</strong></p>
                                                <ul>
                                                    <li>Pertahankan pola makan sehat dan seimbang</li>
                                                    <li>Lakukan aktivitas fisik secara teratur</li>
                                                    <li>Monitor berat badan ideal</li>
                                                    <li>Lakukan pemeriksaan kesehatan rutin setiap 6 bulan</li>
                                                    <li>Hindari konsumsi gula berlebihan</li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-calendar-alt"></i> Informasi Prediksi
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Tanggal Prediksi:</strong> <?= date('d F Y H:i', strtotime($prediksi['created_at'])) ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Model yang Digunakan:</strong> Random Forest Classifier</p>
                                            </div>
                                        </div>
                                        <p class="text-muted mb-0">
                                            <small><i class="fas fa-info-circle"></i> Prediksi ini menggunakan model Machine Learning yang dilatih dengan dataset PIMA Indians Diabetes.</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <a href="<?= base_url('prediksi') ?>" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Prediksi
                                </a>
                                <button onclick="window.print()" class="btn btn-primary">
                                    <i class="fas fa-print"></i> Cetak Laporan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
