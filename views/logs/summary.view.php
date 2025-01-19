<!-- views/logs/summary.view.php -->
<div class="container mt-4" id="normalLayout">
    <h2>Logs Summary (On-Screen)</h2>

    <!-- Existing button (unchanged) -->
    <a href="<?= BASE_PATH ?>/logs/summary-print" class="btn btn-primary mb-3">
        Go to Print Layout
    </a>

    <table class="table table-bordered">
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

    <!-- New button to reveal/print the hidden layout on the same page -->
    <button id="showPrintLayout" class="btn btn-secondary">Print Here</button>
</div>

<!-- Hidden layout for printing (initially display:none on screen) -->
<div class="container mt-4" id="printLayout" style="display:none;">
    <div class="official-header text-center">
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

    <table class="table table-bordered">
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

    <div class="official-footer text-center mt-4">
        <p>Printed by: <?= htmlspecialchars($_SESSION['user_name'] ?? 'Unknown User') ?></p>
        <p>Date: <?= date('F j, Y, g:i a') ?></p>
    </div>
</div>

<!-- Print styles: hide normal layout and show print layout when actually printing -->
<style>
@media print {
    #normalLayout {
        display: none !important;
    }
    #printLayout {
        display: block !important;
    }
    nav,
    #sidebar,
    .navbar,
    .nav,
    .btn {
        display: none !important;
    }
    table, th, td {
        border: 1px solid #000;
        border-collapse: collapse;
    }
}
</style>

<!-- Simple script to show the hidden print layout and open the print dialog -->
<script>
document.getElementById('showPrintLayout').addEventListener('click', function() {
    document.getElementById('printLayout').style.display = 'block';
    window.print();
    // Optional: hide the print layout again after printing
    // document.getElementById('printLayout').style.display = 'none';
});
</script>
