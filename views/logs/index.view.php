<!-- views/logs/index.view.php -->
<div class="container mt-4">
    <h2>RFID Logs</h2>

    <!-- Button to view/print logs summary -->
    <a href="<?= BASE_PATH ?>/logs/summary" class="btn btn-secondary mb-3">Print Summary</a>
    
    <!-- Filters -->
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
            </form>
        </div>
    </div>

    <!-- Count Display -->
    <div class="alert alert-info">
        Total Entries: <?= $count ?>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Student Name</th>
                            <th>RFID</th>
                            <th>Program</th>
                            <th>Department</th>
                            <!-- New column for Time In/Out -->
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
                                <!-- Display the "type" field (in/out) -->
                                <td><?= htmlspecialchars(ucfirst($log['type'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
