<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Print-Only Logs Summary</title>
    <style>
        /* Hide navbars, sidebars, etc. when printing */
        @media print {
            /* Hide any elements with these selectors */
            nav,
            #sidebar,
            .navbar,
            .nav,
            button,
            .btn {
                display: none !important;
            }
            body {
                font-family: Arial, sans-serif;
                margin: 1in;
            }
            table, th, td {
                border: 1px solid #000;
                border-collapse: collapse;
                page-break-inside: avoid;
            }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
        }
        /* Screen Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            margin-bottom: 30px;
        }
        th, td {
            padding: 8px;
        }
        .official-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .official-footer {
            margin-top: 30px;
            text-align: center;
        }
        button {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<button onclick="window.print();">Print</button>

<div class="official-header">
    <h2>ISABELA STATE UNIVERSITY</h2>
    <h3>Roxas Campus</h3>
    <h3>LIBRARY</h3>
    <br>
    <h3>LOGS SUMMARY</h3>
    <p>Date Range: 
        <?= htmlspecialchars($dateFrom ?: 'All') ?> 
        - <?= htmlspecialchars($dateTo ?: 'All') ?>
    </p>
</div>

<table>
    <thead>
    <tr>
        <th>College</th>
        <th>Male</th>
        <th>Female</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($logSummary as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['college']) ?></td>
            <td><?= (int)$row['male'] ?></td>
            <td><?= (int)$row['female'] ?></td>
            <td><?= (int)$row['total'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="official-footer">
    <p>Printed by: <?= htmlspecialchars($_SESSION['user_name'] ?? 'Unknown User') ?></p>
    <p>Date: <?= date('F j, Y, g:i a') ?></p>
</div>

</body>
</html>
