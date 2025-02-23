<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Print Filtered Logs</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }
        @media print {
            .print-button {
                display: none !important;
            }
            nav, #sidebar, .navbar {
                display: none !important;
            }
            table, th, td {
                border: 1px solid #000;
                border-collapse: collapse;
            }
            th, td {
                padding: 6px;
            }
        }
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            width: 95%;
            margin: 0 auto;
        }
        .header {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }
        table {
            margin-bottom: 20px;
            width: 100%;
        }
        .footer {
            text-align: right;
            margin-top: 20px;
        }
        .header .logo {
            margin-right: 20px;
        }
    </style>
</head>
<body>

<button class="print-button" onclick="window.print();">Print</button>

<div class="container">
    <div class="header">
        <!-- Logo on the left -->
        <div class="logo">
            <img src="assets/img/school-logo.png" alt="School Logo" style="width: 90px;">
        </div>
        <div>
            <h2>ISABELA STATE UNIVERSITY - Roxas Campus</h2>
            <h3>LIBRARY - Filtered Logs</h3>
            <p>
                Date Range:
                <?= htmlspecialchars($_GET['date_from'] ?? '') ?>
                to
                <?= htmlspecialchars($_GET['date_to'] ?? '') ?>
            </p>
            <p>
                <?php
                $logType = $_GET['type'] ?? '';
                if ($logType === 'in') {
                    echo "Type: IN only<br>";
                } elseif ($logType === 'out') {
                    echo "Type: OUT only<br>";
                } else {
                    echo "Type: All (IN & OUT)<br>";
                }
                ?>
                <strong>Total Entries:</strong> <?= $count ?> &nbsp;|&nbsp;
                <strong>Male (IN):</strong> <?= $maleCount ?> &nbsp;|&nbsp;
                <strong>Female (IN):</strong> <?= $femaleCount ?>
            </p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date &amp; Time</th>
                <th>Student Name</th>
                <th>Program</th>
                <th>Department</th>
                <th>In/Out</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($logs)): ?>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= date('Y-m-d H:i:s', strtotime($log['timestamp'])) ?></td>
                    <td><?= htmlspecialchars($log['firstname'].' '.$log['lastname']) ?></td>
                    <td><?= htmlspecialchars($log['program']) ?></td>
                    <td><?= htmlspecialchars($log['department']) ?></td>
                    <td><?= htmlspecialchars(ucfirst($log['type'])) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center;">No logs found for the given filters.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <small>
            Printed by: <?= htmlspecialchars($_SESSION['user_name'] ?? 'Unknown') ?>
            | <?= date('F j, Y, g:i A') ?>
        </small>
    </div>
</div>

</body>
</html>
