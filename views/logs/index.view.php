<!-- views/logs/index.view.php -->
<div class="container mt-4">
    <h2>RFID Logs</h2>

    <a href="<?= BASE_PATH ?>/logs/summary" class="btn btn-secondary mb-3">Print Summary</a>
    
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" 
                           value="<?= htmlspecialchars($filters['date_from']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" 
                           value="<?= htmlspecialchars($filters['date_to']) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Program</label>
                    <select name="program" class="form-select">
                        <option value="">All Programs</option>
                        <?php foreach ($programs as $program): ?>
                            <option value="<?= htmlspecialchars($program['program']) ?>" 
                                <?= $filters['program'] === $program['program'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($program['program']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Department</label>
                    <select name="department" class="form-select">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= htmlspecialchars($dept['department']) ?>"
                                <?= $filters['department'] === $dept['department'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dept['department']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <a href="<?= BASE_PATH ?>/logs/print?date_from=<?= urlencode($filters['date_from']) ?>&date_to=<?= urlencode($filters['date_to']) ?>&program=<?= urlencode($filters['program']) ?>&department=<?= urlencode($filters['department']) ?>"
                       class="btn btn-secondary"
                    >
                       Print Filtered
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-info">
        Total Entries: <?= $count ?><br>
        Male: <?= $maleCount ?><br>
        Female: <?= $femaleCount ?>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date &amp; Time</th>
                            <th>Student Name</th>
                            <th>RFID</th>
                            <th>Program</th>
                            <th>Department</th>
                            <th>Time In/Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= date('Y-m-d H:i:s', strtotime($log['timestamp'])) ?></td>
                                <td><?= htmlspecialchars($log['firstname'] . ' ' . $log['lastname']) ?></td>
                                <td><?= htmlspecialchars($log['rfid']) ?></td>
                                <td><?= htmlspecialchars($log['program']) ?></td>
                                <td><?= htmlspecialchars($log['department']) ?></td>
                                <td><?= htmlspecialchars(ucfirst($log['type'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
