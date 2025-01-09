<!-- views/logs/summary.view.php -->
<div class="container mt-4">
    <h2>Logs Summary (On-Screen)</h2>
    
    <!-- Link to the print-only page -->
    <p>
        <a href="<?= BASE_PATH ?>/logs/summary-print" class="btn btn-primary">
            Go to Print Layout
        </a>
    </p>

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
</div>
