<!-- views/students/view.view.php -->

<div class="container mt-4">
    <h2>Student Details</h2>
    <a href="<?= BASE_PATH ?>/students" class="btn btn-secondary mb-3">Back to Students</a>

    <div class="card">
        <div class="card-body">
            <?php if ($student['image'] && file_exists($student['image'])): ?>
                <img src="<?= BASE_PATH ?>/<?= htmlspecialchars($student['image']) ?>" alt="Student Image" class="img-thumbnail mb-3" width="150">
            <?php else: ?>
                <p>No Image Available</p>
            <?php endif; ?>

            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <td><?= htmlspecialchars($student['id']) ?></td>
                </tr>
                <tr>
                    <th>RFID</th>
                    <td><?= htmlspecialchars($student['rfid']) ?></td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td><?= htmlspecialchars($student['firstname']) ?></td>
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
                    <td><?= htmlspecialchars($student['course']) ?></td>
                </tr>
                <tr>
                    <th>Section</th>
                    <td><?= htmlspecialchars($student['section']) ?></td>
                </tr>
                <tr>
                    <th>Department</th>
                    <td><?= htmlspecialchars($student['department']) ?></td>
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
</div>
