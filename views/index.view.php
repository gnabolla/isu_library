<!-- views/index.view.php -->

<div class="content">
    <div class="container">
        <!-- Page Header -->
        <div class="row">
            <div class="col-md-12 page-header">
                <div class="page-pretitle">Overview</div>
                <h2 class="page-title">Dashboard</h2>
            </div>
        </div>

        <!-- Informative Cards -->
        <div class="row mb-4">
            <!-- Card 1: Total Students -->
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="teal fas fa-user-graduate"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">Total Students</p>
                                    <span class="number"><?= htmlspecialchars($data['totalStudents']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                <i class="fas fa-calendar"></i> Total Registered
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Total Access Logs -->
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="olive fas fa-file-alt"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">Access Logs</p>
                                    <span class="number"><?= htmlspecialchars($data['totalLogs']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                <i class="fas fa-calendar"></i> Total Logs
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Total Departments -->
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="violet fas fa-building"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">Departments</p>
                                    <span class="number"><?= htmlspecialchars($data['totalDepartments']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                <i class="fas fa-building"></i> Active Departments
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 4: Latest Student Added -->
            <div class="col-sm-6 col-md-6 col-lg-3 mt-3">
                <div class="card">
                    <div class="content">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="icon-big text-center">
                                    <i class="orange fas fa-user-plus"></i>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="detail">
                                    <p class="detail-subtitle">Latest Student</p>
                                    <span class="number"><?= htmlspecialchars($data['latestStudent']['firstname'] . ' ' . $data['latestStudent']['lastname']) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <hr />
                            <div class="stats">
                                <i class="fas fa-user-clock"></i> Added on <?= htmlspecialchars(date('F j, Y', strtotime($data['latestStudent']['created_at']))) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <!-- Students per Department (Bar Chart) -->
            <div class="col-md-6">
                <div class="card">
                    <div class="content">
                        <div class="head">
                            <h5 class="mb-0">Students per Department</h5>
                            <p class="text-muted">Number of students in each department</p>
                        </div>
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="departmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Access Logs (Line Chart) -->
            <div class="col-md-6">
                <div class="card">
                    <div class="content">
                        <div class="head">
                            <h5 class="mb-0">Monthly Access Logs</h5>
                            <p class="text-muted">Access logs aggregated monthly</p>
                        </div>
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="logsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 5 Active Students (Bar Chart) -->
            <div class="col-md-6">
                <div class="card">
                    <div class="content">
                        <div class="head">
                            <h5 class="mb-0">Top 5 Active Students</h5>
                            <p class="text-muted">Students with the most access logs</p>
                        </div>
                        <div class="canvas-wrapper">
                            <canvas class="chart" id="topStudentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="row">
            <!-- Top 5 Active Students Table -->
            <div class="col-md-6">
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
                                    <?php foreach ($data['topStudents'] as $student): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></td>
                                            <td class="text-end"><?= htmlspecialchars($student['log_count']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Embed Data for Charts -->
    <script>
        // Students per Department Data
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

        // Monthly Access Logs Data
        const logsData = {
            labels: <?= json_encode(array_column($data['logs'], 'log_month')) ?>,
            datasets: [{
                label: 'Access Logs',
                data: <?= json_encode(array_column($data['logs'], 'count')) ?>,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }]
        };

        // Top 5 Active Students Data
        const topStudentsData = {
            labels: <?= json_encode(array_map(function($student) {
                return htmlspecialchars($student['firstname'] . ' ' . $student['lastname']);
            }, $data['topStudents'])) ?>,
            datasets: [{
                label: 'Number of Logs',
                data: <?= json_encode(array_column($data['topStudents'], 'log_count')) ?>,
                backgroundColor: 'rgba(255, 159, 64, 0.6)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        };
    </script>

    <!-- Initialize Charts -->
    <script>
        // Function to initialize a chart
        function initChart(ctx, type, data, options = {}) {
            new Chart(ctx, {
                type: type,
                data: data,
                options: options
            });
        }

        // Department Bar Chart
        const deptCtx = document.getElementById('departmentChart').getContext('2d');
        initChart(deptCtx, 'bar', departmentData, {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: false
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        });

        // Monthly Access Logs Line Chart
        const logsCtx = document.getElementById('logsChart').getContext('2d');
        initChart(logsCtx, 'line', logsData, {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: false
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        });

        // Top 5 Active Students Bar Chart
        const topStudentsCtx = document.getElementById('topStudentsChart').getContext('2d');
        initChart(topStudentsCtx, 'bar', topStudentsData, {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: false
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        });
    </script>
</div>
