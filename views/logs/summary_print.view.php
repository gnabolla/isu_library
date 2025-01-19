<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Print-Only Logs Summary</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }
        @media print {
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
            }
            table, th, td {
                border: 1px solid #000;
                border-collapse: collapse;
            }
            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }
            tr {
                page-break-inside: avoid;
            }
            /* OPTIONAL: If you want the footer fixed at the bottom of the page */
            /*
            .official-footer {
                position: fixed;
                bottom: 1cm;
                left: 1cm;
                right: 1cm;
            }
            */
        }
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
            /* Flex container for left/right alignment */
            display: flex;
            justify-content: space-between;
        }
        .official-footer > div {
            /* Adjust these if you want more spacing or different styling */
            font-size: 0.95rem;
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
    <div>
        Printed by: <?= htmlspecialchars($_SESSION['user_name'] ?? 'Unknown User') ?>
    </div>
    <div>
        Date: <?= date('F j, Y, g:i a') ?>
    </div>
</div>

</body>
</html>
