<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('dashboard') ?>" class="brand-link">
        <img src="<?= base_url('logo.png') ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8; max-height: 33px;">
        <span class="brand-text font-weight-light">TSC</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <i class="fas fa-user-md img-circle elevation-2"></i>
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= session()->get('nama') ?? 'Guest' ?></a>
                <small class="text-light"><?= ucfirst(session()->get('role') ?? 'guest') ?></small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= current_url() == base_url('dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <li class="nav-header">DATA MASTER</li>
                
                <li class="nav-item">
                    <a href="<?= base_url('pasien') ?>" class="nav-link <?= strpos(current_url(), 'pasien') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-injured"></i>
                        <p>Data Pasien</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('prediksi') ?>" class="nav-link <?= strpos(current_url(), 'prediksi') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-stethoscope"></i>
                        <p>Prediksi Diabetes</p>
                    </a>
                </li>
                
                <?php if(session()->get('role') == 'admin'): ?>
                <li class="nav-item">
                    <a href="<?= base_url('petugas') ?>" class="nav-link <?= strpos(current_url(), 'petugas') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Petugas</p>
                    </a>
                </li>
                <?php endif; ?>
                
                <li class="nav-header">LAPORAN</li>
                
                <li class="nav-item">
                    <a href="<?= base_url('laporan') ?>" class="nav-link <?= strpos(current_url(), 'laporan') !== false ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Laporan Hasil</p>
                    </a>
                </li>
                
                <li class="nav-header">AKUN</li>
                
                <li class="nav-item">
                    <a href="<?= base_url('auth/logout') ?>" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
