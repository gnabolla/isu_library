<!-- views/logs/index.view.php -->
<div class="container mt-4">
    <h2>RFID Logs</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <!-- NEW: Range selector -->
                <div class="col-md-2">
                    <label class="form-label">Range</label>
                    <select name="range" class="form-select">
                        <option value=""     <?= ($range === '')     ? 'selected' : '' ?>>Custom</option>
                        <option value="day"  <?= ($range === 'day')  ? 'selected' : '' ?>>Day</option>
                        <option value="week" <?= ($range === 'week') ? 'selected' : '' ?>>Week</option>
                        <option value="month"<?= ($range === 'month')? 'selected' : '' ?>>Month</option>
                    </select>
                </div>

                <!-- Existing date filters (used when range = Custom or overridden by user) -->
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" 
                           value="<?= htmlspecialchars($filters['date_from']) ?>">
                </div>
                <div class="col-md-2">
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
                                <?= ($filters['program'] === $program['program']) ? 'selected' : '' ?>>
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
                                <?= ($filters['department'] === $dept['department']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dept['department']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- NEW: Filter by IN/OUT -->
                <div class="col-md-2">
                    <label class="form-label">Log Type</label>
                    <select name="type" class="form-select">
                        <option value="">All</option>
                        <option value="in"  <?= ($filters['type'] === 'in')  ? 'selected' : '' ?>>IN</option>
                        <option value="out" <?= ($filters['type'] === 'out') ? 'selected' : '' ?>>OUT</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                </div>

                <!-- Print Filtered Button -->
                <div class="col-md-12">
                    <a href="<?= BASE_PATH ?>/logs/print?
                        date_from=<?= urlencode($filters['date_from']) ?>&
                        date_to=<?= urlencode($filters['date_to']) ?>&
                        program=<?= urlencode($filters['program']) ?>&
                        department=<?= urlencode($filters['department']) ?>&
                        type=<?= urlencode($filters['type']) ?>&
                        "
                       class="btn btn-secondary"
                    >
                       Print Filtered
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- NEW: Subtle Summary: total logs, male/female who actually entered -->
    <div class="alert alert-info">
        <strong>Total Logs:</strong> <?= $count ?> &nbsp;|&nbsp;
        <strong>Male (IN):</strong> <?= $maleCount ?> &nbsp;|&nbsp;
        <strong>Female (IN):</strong> <?= $femaleCount ?> &nbsp;|&nbsp;
        <strong>All (IN):</strong> <?= ($maleCount + $femaleCount) ?>
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
