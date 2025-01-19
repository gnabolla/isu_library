<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Print Filtered Logs</title>
    <style>
        @page {
            size: A4;
            margin: 1cm; /* You can adjust this to 0.5cm, etc., if you still see cutoff borders */
        }
        @media print {
            /* Hide the print button during print */
            .print-button {
                display: none !important;
            }
            /* Hide navbar, etc. */
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
            /* Table and content area at 95% to prevent right-edge cutoff */
            width: 95%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            margin-bottom: 20px;
            width: 100%; /* within the .container at 95% of page */
        }
        .footer {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- The print button will be shown normally but hidden in print preview -->
<button class="print-button" onclick="window.print();">Print</button>

<div class="container">
    <div class="header">
        <h2>ISABELA STATE UNIVERSITY - Roxas Campus</h2>
        <h3>LIBRARY - Filtered Logs</h3>
        <p>
            Date Range:
            <?= htmlspecialchars($_GET['date_from'] ?? '') ?>
            to
            <?= htmlspecialchars($_GET['date_to'] ?? '') ?>
        </p>
        <p>
            <strong>Total Entries:</strong> <?= $count ?> &nbsp;|&nbsp;
            <strong>Male:</strong> <?= $maleCount ?> &nbsp;|&nbsp;
            <strong>Female:</strong> <?= $femaleCount ?>
        </p>
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
