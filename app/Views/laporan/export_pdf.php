<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - PDF Export</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        .filter-info {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 11px;
        }
        .filter-info h3 {
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        table th {
            background-color: #4CAF50;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        table td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .result-positive {
            color: #d32f2f;
            font-weight: bold;
        }
        .result-negative {
            color: #388e3c;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-before: always;
        }
        .summary {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #e8f5e8;
            border-radius: 5px;
        }
        .summary h3 {
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= $title ?></h1>
        <p>Sistem Prediksi Diabetes - Random Forest Model</p>
        <p>Export Date: <?= $export_date ?></p>
    </div>

    <?php if ($filters['start_date'] || $filters['end_date'] || $filters['id_pasien'] || $filters['hasil'] !== null): ?>
    <div class="filter-info">
        <h3>Filter Applied:</h3>
        <ul style="margin: 0; padding-left: 20px;">
            <?php if ($filters['start_date']): ?>
                <li>Start Date: <?= $filters['start_date'] ?></li>
            <?php endif; ?>
            <?php if ($filters['end_date']): ?>
                <li>End Date: <?= $filters['end_date'] ?></li>
            <?php endif; ?>
            <?php if ($filters['id_pasien']): ?>
                <li>Patient ID: <?= $filters['id_pasien'] ?></li>
            <?php endif; ?>
            <?php if ($filters['hasil'] !== null && $filters['hasil'] !== ''): ?>
                <li>Result: <?= $filters['hasil'] == 1 ? 'Diabetes' : 'Not Diabetes' ?></li>
            <?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="summary">
        <h3>Summary</h3>
        <p>Total Records: <?= count($prediksi) ?></p>
        <?php
            $positif = array_filter($prediksi, function($item) { return $item['hasil'] == 1; });
            $negatif = array_filter($prediksi, function($item) { return $item['hasil'] == 0; });
            $persentase_positif = count($prediksi) > 0 ? round((count($positif) / count($prediksi)) * 100, 2) : 0;
        ?>
        <p>Diabetes Cases: <?= count($positif) ?> (<?= $persentase_positif ?>%)</p>
        <p>Non-Diabetes Cases: <?= count($negatif) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Patient Name</th>
                <th>Date</th>
                <th>Glucose</th>
                <th>BMI</th>
                <th>Age</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($prediksi as $p): ?>
                <?php if ($no > 1 && $no % 25 == 1): ?>
                    </tbody>
                    </table>
                    <div class="page-break"></div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Patient Name</th>
                                <th>Date</th>
                                <th>Glucose</th>
                                <th>BMI</th>
                                <th>Age</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php endif; ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($p['nama_pasien']) ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($p['created_at'])) ?></td>
                    <td><?= esc($p['glucose']) ?></td>
                    <td><?= esc($p['bmi']) ?></td>
                    <td><?= esc($p['age']) ?></td>
                    <td class="<?= $p['hasil'] == 1 ? 'result-positive' : 'result-negative' ?>">
                        <?= $p['hasil'] == 1 ? 'Diabetes' : 'Not Diabetes' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Generated by Diabetes Prediction System - CodeIgniter 4 + AdminLTE + Python Random Forest</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html>
