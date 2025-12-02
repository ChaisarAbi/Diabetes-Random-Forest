<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
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
                        <h3><?= $total_pasien ?></h3>
                        <p>Total Pasien</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-injured"></i>
                    </div>
                    <a href="<?= base_url('pasien') ?>" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $total_prediksi ?></h3>
                        <p>Total Prediksi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <a href="<?= base_url('prediksi') ?>" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $total_petugas ?></h3>
                        <p>Total Petugas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="<?= base_url('petugas') ?>" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $total_positif ?></h3>
                        <p>Hasil Positif Diabetes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <a href="<?= base_url('laporan') ?>" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Statistik Prediksi Bulanan (<?= date('Y') ?>)</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="monthlyChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Prediksi Terbaru</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            <?php foreach($recent_predictions as $prediksi): ?>
                            <li class="item">
                                <div class="product-info">
                                    <a href="<?= base_url('prediksi/detail/' . $prediksi['id']) ?>" class="product-title">
                                        <?= $prediksi['nama_pasien'] ?>
                                        <span class="badge badge-<?= $prediksi['hasil'] == 1 ? 'danger' : 'success' ?> float-right">
                                            <?= $prediksi['hasil'] == 1 ? 'Diabetes' : 'Sehat' ?>
                                        </span>
                                    </a>
                                    <span class="product-description">
                                        Glucose: <?= $prediksi['glucose'] ?> | Age: <?= $prediksi['age'] ?>
                                        <br>
                                        <small><?= date('d M Y', strtotime($prediksi['created_at'])) ?></small>
                                    </span>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Chart
    var ctx = document.getElementById('monthlyChart').getContext('2d');
    var monthlyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($monthly_stats['months']) ?>,
            datasets: [
                {
                    label: 'Total Prediksi',
                    data: <?= json_encode($monthly_stats['totals']) ?>,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Hasil Positif',
                    data: <?= json_encode($monthly_stats['positifs']) ?>,
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Trend Prediksi Diabetes'
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
