<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Laporan Hasil Prediksi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Hasil Prediksi</h3>
                        <p>Filter & Export Data</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <a href="<?= base_url('laporan/hasil') ?>" class="small-box-footer">
                        Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>Laporan Bulanan</h3>
                        <p>Statistik Per Bulan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <a href="<?= base_url('laporan/bulanan') ?>" class="small-box-footer">
                        Lihat Laporan <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>Export PDF</h3>
                        <p>Download Laporan PDF</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <a href="<?= base_url('laporan/exportPdf') ?>" class="small-box-footer">
                        Export PDF <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>Export Excel</h3>
                        <p>Download Laporan Excel</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <a href="<?= base_url('laporan/exportExcel') ?>" class="small-box-footer">
                        Export Excel <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Panduan Laporan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fas fa-info-circle text-info"></i> Hasil Prediksi</h5>
                                <p>Laporan detail hasil prediksi dengan filter berdasarkan:</p>
                                <ul>
                                    <li>Tanggal prediksi</li>
                                    <li>Nama pasien</li>
                                    <li>Hasil prediksi (Diabetes/Tidak Diabetes)</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fas fa-chart-line text-success"></i> Laporan Bulanan</h5>
                                <p>Statistik prediksi per bulan dengan grafik:</p>
                                <ul>
                                    <li>Jumlah prediksi per hari</li>
                                    <li>Persentase hasil positif</li>
                                    <li>Tren prediksi bulanan</li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5><i class="fas fa-file-pdf text-warning"></i> Export PDF</h5>
                                <p>Download laporan dalam format PDF untuk:</p>
                                <ul>
                                    <li>Dokumentasi resmi</li>
                                    <li>Presentasi</li>
                                    <li>Arsip data</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fas fa-file-excel text-danger"></i> Export Excel</h5>
                                <p>Download laporan dalam format Excel untuk:</p>
                                <ul>
                                    <li>Analisis data lanjutan</li>
                                    <li>Pengolahan data statistik</li>
                                    <li>Backup database</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
