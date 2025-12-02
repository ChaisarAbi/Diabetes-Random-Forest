<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Tugu Sawangan Cinangka - Sistem Prediksi Diabetes' ?></title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AdminLTE Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --light-green: #d4edda;
            --light-orange: #fff3cd;
            --light-yellow: #f8f9fa;
        }
        
        .bg-light-green {
            background-color: var(--light-green) !important;
        }
        
        .bg-light-orange {
            background-color: var(--light-orange) !important;
        }
        
        .bg-light-yellow {
            background-color: var(--light-yellow) !important;
        }
        
        .sidebar-dark-primary {
            background-color: #28a745 !important;
        }
        
        .navbar-dark {
            background-color: #218838 !important;
        }
        
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        
        .card-header {
            background-color: var(--light-yellow);
        }
        
        .info-box {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .info-box-icon {
            border-radius: 10px 0 0 10px;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
