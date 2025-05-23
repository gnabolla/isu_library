<div class="container mt-4">
    <h2>Student Details</h2>
    <a href="<?= BASE_PATH ?>/students" class="btn btn-secondary mb-3">Back to Students</a>

    <div class="card">
        <div class="card-body">
            <?php
                $imgPath = $student['image'];
                if (!$imgPath || !file_exists($imgPath)) {
                    $imgPath = 'assets/img/default-avatar.png';
                }
            ?>
            <img src="<?= BASE_PATH ?>/<?= htmlspecialchars($imgPath) ?>"
                 alt="Student Image" class="img-thumbnail mb-3" width="150">

            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td><?= htmlspecialchars($student['id']) ?></td>
                </tr>
                <tr>
                    <th>Student ID</th>
                    <td><?= htmlspecialchars($student['rfid']) ?></td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td><?= htmlspecialchars($student['firstname']) ?></td>
                </tr>
                <tr>
                    <th>Middle Name</th>
                    <td><?= htmlspecialchars($student['middlename']) ?></td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><?= htmlspecialchars($student['lastname']) ?></td>
                </tr>
                <tr>
                    <th>Year</th>
                    <td><?= htmlspecialchars($student['year']) ?></td>
                </tr>
                <tr>
                    <th>Course</th>
                    <td><?= htmlspecialchars($student['course_name'] ?? '') ?></td>
                </tr>
                <tr>
                    <th>Section</th>
                    <td><?= htmlspecialchars($student['section']) ?></td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td><?= htmlspecialchars($student['department_name'] ?? '') ?></td>
                </tr>
                <tr>
                    <th>Sex</th>
                    <td><?= htmlspecialchars($student['sex']) ?></td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td><?= htmlspecialchars($student['created_at']) ?></td>
                </tr>
                <tr>
                    <th>Updated At</th>
                    <td><?= htmlspecialchars($student['updated_at']) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h3>Logs</h3>
            <?php if (!empty($studentLogs)): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Log Type</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($studentLogs as $log): ?>
                        <tr>
                            <td><?= date('Y-m-d H:i:s', strtotime($log['timestamp'])) ?></td>
                            <td><?= htmlspecialchars(ucfirst($log['type'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No logs found for this student.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
