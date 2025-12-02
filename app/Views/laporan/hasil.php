<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Hasil Prediksi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('laporan') ?>">Laporan</a></li>
                    <li class="breadcrumb-item active">Hasil Prediksi</li>
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
                        <h3 class="card-title">Filter Laporan</h3>
                    </div>
                    <form method="get" action="<?= base_url('laporan/hasil') ?>">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tanggal Mulai</label>
                                        <input type="date" name="start_date" class="form-control" value="<?= $filters['start_date'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tanggal Akhir</label>
                                        <input type="date" name="end_date" class="form-control" value="<?= $filters['end_date'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Pasien</label>
                                        <select name="id_pasien" class="form-control">
                                            <option value="">Semua Pasien</option>
                                            <?php foreach ($pasien as $p): ?>
                                                <option value="<?= $p['id'] ?>" <?= ($filters['id_pasien'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                                                    <?= esc($p['nama']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Hasil</label>
                                        <select name="hasil" class="form-control">
                                            <option value="">Semua Hasil</option>
                                            <option value="0" <?= ($filters['hasil'] ?? '') === '0' ? 'selected' : '' ?>>Tidak Diabetes</option>
                                            <option value="1" <?= ($filters['hasil'] ?? '') === '1' ? 'selected' : '' ?>>Diabetes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="<?= base_url('laporan/hasil') ?>" class="btn btn-default">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                            <a href="<?= base_url('laporan/export/pdf?' . http_build_query($filters)) ?>" class="btn btn-warning float-right">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <a href="<?= base_url('laporan/export/excel?' . http_build_query($filters)) ?>" class="btn btn-success float-right mr-2">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $statistics['total'] ?></h3>
                        <p>Total Prediksi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $statistics['positif'] ?></h3>
                        <p>Hasil Diabetes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $statistics['negatif'] ?></h3>
                        <p>Hasil Tidak Diabetes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $statistics['persentase_positif'] ?>%</h3>
                        <p>Persentase Diabetes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Hasil Prediksi</h3>
                    </div>
                    <div class="card-body">
                        <table id="hasilTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>Tanggal</th>
                                    <th>Pregnancies</th>
                                    <th>Glucose</th>
                                    <th>Blood Pressure</th>
                                    <th>Skin Thickness</th>
                                    <th>Insulin</th>
                                    <th>BMI</th>
                                    <th>DPF</th>
                                    <th>Age</th>
                                    <th>Hasil</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($prediksi as $p): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($p['nama_pasien']) ?></td>
                                        <td><?= date('d-m-Y H:i', strtotime($p['created_at'])) ?></td>
                                        <td><?= esc($p['pregnancies']) ?></td>
                                        <td><?= esc($p['glucose']) ?></td>
                                        <td><?= esc($p['blood_pressure']) ?></td>
                                        <td><?= esc($p['skin_thickness']) ?></td>
                                        <td><?= esc($p['insulin']) ?></td>
                                        <td><?= esc($p['bmi']) ?></td>
                                        <td><?= esc($p['dpf']) ?></td>
                                        <td><?= esc($p['age']) ?></td>
                                        <td>
                                            <?php if ($p['hasil'] == 1): ?>
                                                <span class="badge badge-danger">Diabetes</span>
                                            <?php else: ?>
                                                <span class="badge badge-success">Tidak Diabetes</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#hasilTable').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#hasilTable_wrapper .col-md-6:eq(0)');
    });
</script>
<?= $this->endSection() ?>
