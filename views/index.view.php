<!-- views/index.view.php -->

<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12 page-header">
                <div class="page-pretitle">Overview</div>
                <h2 class="page-title">Dashboard</h2>
            </div>
        </div>

        <!-- Row 1: Four cards -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-4 text-center">
                                <i class="teal fas fa-user-graduate fa-2x"></i>
                            </div>
                            <div class="col-8">
                                <p class="detail-subtitle">Total Students</p>
                                <span class="number"><?= htmlspecialchars($data['totalStudents']) ?></span>
                            </div>
                        </div>
                        <hr />
                        <div class="stats">
                            <i class="fas fa-calendar"></i> Total Registered
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-4 text-center">
                                <i class="olive fas fa-file-alt fa-2x"></i>
                            </div>
                            <div class="col-8">
                                <p class="detail-subtitle">Access Logs</p>
                                <span class="number"><?= htmlspecialchars($data['totalLogs']) ?></span>
                            </div>
                        </div>
                        <hr />
                        <div class="stats">
                            <i class="fas fa-calendar"></i> Total Logs
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-4 text-center">
                                <i class="violet fas fa-building fa-2x"></i>
                            </div>
                            <div class="col-8">
                                <p class="detail-subtitle">Departments</p>
                                <span class="number"><?= htmlspecialchars($data['totalDepartments']) ?></span>
                            </div>
                        </div>
                        <hr />
                        <div class="stats">
                            <i class="fas fa-building"></i> Active Depts
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="detail-subtitle mb-0">Attendance</p>
                                    <select class="form-select form-select-sm w-auto"
                                            onchange="location.href='?range='+this.value">
                                        <option value="day"   <?= $data['attendees']['range']=='day'?'selected':'' ?>>Day</option>
                                        <option value="week"  <?= $data['attendees']['range']=='week'?'selected':'' ?>>Week</option>
                                        <option value="month" <?= $data['attendees']['range']=='month'?'selected':'' ?>>Month</option>
                                    </select>
                                </div>
                                <span class="number mt-2">
                                    <?= $data['attendees']['total'] ?>
                                </span>
                            </div>
                        </div>
                        <hr />
                        <div class="stats">
                            Male: <?= $data['attendees']['male'] ?> | Female: <?= $data['attendees']['female'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Next four cards -->
        <div class="row mb-4">
            <div class="col-sm-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-4 text-center">
                                <i class="fas fa-user fa-2x text-primary"></i>
                            </div>
                            <div class="col-8">
                                <p class="detail-subtitle">Male Students</p>
                                <span class="number"><?= htmlspecialchars($data['totalMaleStudents']) ?></span>
                            </div>
                        </div>
                        <hr />
                        <div class="stats">
                            <i class="fas fa-male"></i> Total Males
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-4 text-center">
                                <i class="fas fa-female fa-2x text-pink"></i>
                            </div>
                            <div class="col-8">
                                <p class="detail-subtitle">Female Students</p>
                                <span class="number"><?= htmlspecialchars($data['totalFemaleStudents']) ?></span>
                            </div>
                        </div>
                        <hr />
                        <div class="stats">
                            <i class="fas fa-female"></i> Total Females
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-4 text-center">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                            <div class="col-8">
                                <p class="detail-subtitle">Peak Hour (7d)</p>
                                <span class="number">
                                    <?= ($data['peakHour'] !== null)
                                        ? htmlspecialchars($data['peakHour'] . ":00")
                                        : "N/A"
                                    ?>
                                </span>
                            </div>
                        </div>
                        <hr />
                        <div class="stats">
                            Logs: <?= (int)$data['peakHourCount'] ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-4 text-center">
                                <i class="fas fa-calendar-day fa-2x text-info"></i>
                            </div>
                            <div class="col-8">
                                <p class="detail-subtitle">Peak Day (30d)</p>
                                <span class="number"><?= htmlspecialchars($data['peakDay'] ?? 'N/A') ?></span>
                            </div>
                        </div>
                        <hr />
                        <div class="stats">
                            Logs: <?= (int)$data['peakDayCount'] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Charts -->
        <div class="row">
            <div class="col-md-6 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="head">
                            <h5 class="mb-0">Students per Department</h5>
                            <p class="text-muted">Number of students in each department</p>
                        </div>
                        <div class="canvas-wrapper">
                            <canvas id="departmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="head">
                            <h5 class="mb-0">Monthly Access Logs</h5>
                            <p class="text-muted">Access logs aggregated monthly</p>
                        </div>
                        <div class="canvas-wrapper">
                            <canvas id="logsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 4: Two side-by-side tables -->
        <div class="row">
            <div class="col-md-6 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="head">
                            <h5 class="mb-0">Top 5 Active Students</h5>
                            <p class="text-muted">Students with the most access logs</p>
                        </div>
                        <div class="canvas-wrapper">
                            <table class="table table-striped">
                                <thead class="success">
                                    <tr>
                                        <th>Student Name</th>
                                        <th class="text-end">Log Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <?php if (isset($data['topStudents'][$i])): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($data['topStudents'][$i]['firstname'] . ' ' . $data['topStudents'][$i]['lastname']) ?></td>
                                                <td class="text-end"><?= htmlspecialchars($data['topStudents'][$i]['log_count']) ?></td>
                                            </tr>
                                        <?php else: ?>
                                            <tr>
                                                <td style="color: #ccc;">—</td>
                                                <td class="text-end" style="color: #ccc;">—</td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="head">
                            <h5 class="mb-0">Latest 5 Logs</h5>
                            <p class="text-muted">Most recent scans</p>
                        </div>
                        <div class="canvas-wrapper">
                            <table class="table table-striped">
                                <thead class="success">
                                    <tr>
                                        <th>Date/Time</th>
                                        <th>Student</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['latestLogs'] as $log): ?>
                                        <tr>
                                            <td><?= date('Y-m-d H:i:s', strtotime($log['timestamp'])) ?></td>
                                            <td><?= htmlspecialchars($log['firstname'] . ' ' . $log['lastname']) ?></td>
                                            <td><?= htmlspecialchars(ucfirst($log['type'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 5: Hourly usage, day-of-week usage -->
        <div class="row">
            <div class="col-md-6 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="head">
                            <h5 class="mb-0">Hourly Usage (Past 7 Days)</h5>
                            <p class="text-muted">Logs grouped by hour</p>
                        </div>
                        <div class="canvas-wrapper">
                            <canvas id="hourlyUsageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="head">
                            <h5 class="mb-0">Day-of-Week Usage (Past Month)</h5>
                            <p class="text-muted">Logs by weekday</p>
                        </div>
                        <div class="canvas-wrapper">
                            <canvas id="dayOfWeekChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Data -->
    <script>
        // Students per Department
        const departmentData = {
            labels: <?= json_encode(array_column($data['departments'], 'department')) ?>,
            datasets: [{
                label: 'Number of Students',
                data: <?= json_encode(array_column($data['departments'], 'count')) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };
        const deptCtx = document.getElementById('departmentChart').getContext('2d');
        new Chart(deptCtx, {
            type: 'bar',
            data: departmentData,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Monthly Access Logs
        const logsData = {
            labels: <?= json_encode(array_column($data['logs'], 'log_month')) ?>,
            datasets: [{
                label: 'Access Logs',
                data: <?= json_encode(array_column($data['logs'], 'count')) ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false,
                tension: 0.1
            }]
        };
        const logsCtx = document.getElementById('logsChart').getContext('2d');
        new Chart(logsCtx, {
            type: 'line',
            data: logsData,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Hourly Usage (7d)
        const hourlyLabels = <?= json_encode(array_column($data['usageByHour'], 'hour')) ?>;
        const hourlyCounts = <?= json_encode(array_column($data['usageByHour'], 'count')) ?>;
        const hourlyCtx = document.getElementById('hourlyUsageChart').getContext('2d');
        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: hourlyLabels.map(h => h + ":00"),
                datasets: [{
                    label: 'Logs',
                    data: hourlyCounts,
                    backgroundColor: 'rgba(255, 206, 86, 0.6)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Day-of-Week Usage (past month)
        const dayLabels = <?= json_encode(array_column($data['usageByDayOfWeek'], 'weekday')) ?>;
        const dayCounts = <?= json_encode(array_column($data['usageByDayOfWeek'], 'count')) ?>;
        const dayOfWeekCtx = document.getElementById('dayOfWeekChart').getContext('2d');
        new Chart(dayOfWeekCtx, {
            type: 'bar',
            data: {
                labels: dayLabels,
                datasets: [{
                    label: 'Logs',
                    data: dayCounts,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</div>
