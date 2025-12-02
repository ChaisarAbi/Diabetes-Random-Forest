<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Prediksi Diabetes</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Prediksi Diabetes</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Prediksi</h3>
                        <div class="card-tools">
                            <a href="<?= base_url('prediksi/create') ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Buat Prediksi Baru
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <table id="prediksiTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pasien</th>
                                    <th>Tanggal</th>
                                    <th>Glucose</th>
                                    <th>BMI</th>
                                    <th>Age</th>
                                    <th>Hasil</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($prediksi as $p): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($p['nama_pasien']) ?></td>
                                        <td><?= date('d-m-Y H:i', strtotime($p['created_at'])) ?></td>
                                        <td><?= esc($p['glucose']) ?></td>
                                        <td><?= esc($p['bmi']) ?></td>
                                        <td><?= esc($p['age']) ?> tahun</td>
                                        <td>
                                            <?php if ($p['hasil'] == 1): ?>
                                                <span class="badge badge-danger">Diabetes</span>
                                            <?php else: ?>
                                                <span class="badge badge-success">Tidak Diabetes</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('prediksi/detail/' . $p['id']) ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
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
        $('#prediksiTable').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#prediksiTable_wrapper .col-md-6:eq(0)');
    });
</script>
<?= $this->endSection() ?>
