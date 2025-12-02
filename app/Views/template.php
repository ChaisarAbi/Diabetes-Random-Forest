<?= $this->include('layouts/header') ?>

<!-- Custom CSS for consistency -->
<style>
    /* Consistent card styling */
    .card {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        margin-bottom: 1rem;
        border-radius: 0.5rem;
    }
    
    .card-header {
        padding: 1rem 1.25rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,.125);
        border-top-left-radius: 0.5rem !important;
        border-top-right-radius: 0.5rem !important;
    }
    
    .card-title {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
    }
    
    .card-body {
        padding: 1.25rem;
    }
    
    /* Consistent form styling */
    .form-control {
        border-radius: 0.25rem;
        border: 1px solid #ced4da;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    
    /* Consistent button styling */
    .btn {
        border-radius: 0.25rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    /* Consistent table styling */
    .table {
        font-size: 0.875rem;
        width: 100%;
    }
    
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
        padding: 0.75rem;
        border-top: 1px solid #dee2e6;
    }
    
    .table td {
        padding: 0.75rem;
        vertical-align: middle;
    }
    
    .dataTables_wrapper {
        margin-top: 1rem;
    }
    
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        padding: 0.5rem 0;
    }
    
    /* Consistent small-box colors */
    .bg-light-green {
        background-color: #28a745 !important;
        color: white !important;
    }
    
    .bg-light-orange {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    
    .bg-light-green .small-box-footer,
    .bg-light-orange .small-box-footer {
        color: rgba(255,255,255,.8) !important;
        background-color: rgba(0,0,0,.1) !important;
    }
    
    .bg-light-orange .small-box-footer {
        color: rgba(33,37,41,.8) !important;
    }
    
    /* Consistent spacing */
    .content-header {
        padding: 1.5rem 1rem 1rem;
    }
    
    .content-header h1 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }
    
    .content {
        padding: 0 1rem 1rem;
    }
    
    /* Consistent badge styling */
    .badge {
        font-size: 0.75em;
        padding: 0.35em 0.65em;
        font-weight: 600;
    }
    
    /* Dashboard specific fixes */
    .small-box {
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .small-box .inner {
        padding: 1rem;
    }
    
    .small-box .inner h3 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
    }
    
    .small-box .inner p {
        font-size: 1rem;
        margin: 0;
    }
    
    .small-box .icon {
        font-size: 4rem;
        top: 0.5rem;
        right: 0.5rem;
    }
    
    /* Fix for DataTables responsiveness */
    .dataTables_wrapper .dataTables_scroll {
        margin-bottom: 1rem;
    }
    
    .dataTables_wrapper .dataTables_scrollBody {
        border-bottom: 1px solid #dee2e6 !important;
    }
    
    /* Better spacing for form groups */
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-group label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    /* Alert styling */
    .alert {
        border-radius: 0.5rem;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
    }
    
    /* Breadcrumb styling */
    .breadcrumb {
        padding: 0.5rem 0;
        margin-bottom: 0;
        font-size: 0.875rem;
    }
</style>

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= base_url('dashboard') ?>" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user-circle"></i> <?= session()->get('nama') ?? 'Guest' ?>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">Akun Pengguna</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url('auth/logout') ?>" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<?= $this->include('layouts/sidebar') ?>

<!-- Main content wrapper -->
<div class="content-wrapper">
    <?= $this->renderSection('content') ?>
</div>

<?= $this->include('layouts/footer') ?>
