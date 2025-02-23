<div class="container mt-4">
    <h2>Students</h2>
    <a href="<?= BASE_PATH ?>/students?action=create" class="btn btn-primary mb-3">Add New Student</a>

    <!-- Search and Filters -->
    <form method="GET" action="<?= BASE_PATH ?>/students" class="mb-4">
        <input type="hidden" name="action" value="index">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search name/RFID" 
                       value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-2">
                <select name="year" class="form-select">
                    <option value="">All Years</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>" <?= (isset($filters['year']) && $filters['year'] == $i) ? 'selected' : '' ?>>
                            Year <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="course" class="form-control" placeholder="Course Name" 
                       value="<?= htmlspecialchars($filters['course'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="section" class="form-control" placeholder="Section" 
                       value="<?= htmlspecialchars($filters['section'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="department" class="form-control" placeholder="Department Name" 
                       value="<?= htmlspecialchars($filters['department'] ?? '') ?>">
            </div>
            <div class="col-md-1">
                <select name="sex" class="form-select">
                    <option value="">Sex</option>
                    <option value="Male"   <?= (isset($filters['sex']) && $filters['sex'] == 'Male')   ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= (isset($filters['sex']) && $filters['sex'] == 'Female') ? 'selected' : '' ?>>Female</option>
                    <option value="Other"  <?= (isset($filters['sex']) && $filters['sex'] == 'Other')  ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-secondary">Filter</button>
                <a href="<?= BASE_PATH ?>/students" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>RFID</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                <th>Year</th>
                <th>Course</th>
                <th>Section</th>
                <th>Department</th>
                <th>Sex</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['id']) ?></td>
                        <td><?= htmlspecialchars($s['rfid']) ?></td>
                        <td><?= htmlspecialchars($s['firstname']) ?></td>
                        <td><?= htmlspecialchars($s['middlename']) ?></td>
                        <td><?= htmlspecialchars($s['lastname']) ?></td>
                        <td><?= htmlspecialchars($s['year']) ?></td>
                        <td><?= htmlspecialchars($s['course_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($s['section']) ?></td>
                        <td><?= htmlspecialchars($s['department_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($s['sex']) ?></td>
                        <td>
                            <?php
                                $imgPath = $s['image'];
                                if (!$imgPath || !file_exists($imgPath)) {
                                    $imgPath = 'assets/img/default-avatar.png';
                                }
                            ?>
                            <img src="<?= BASE_PATH ?>/<?= htmlspecialchars($imgPath) ?>" alt="Image" width="50">
                        </td>
                        <td>
                            <a href="<?= BASE_PATH ?>/students?action=view&id=<?= $s['id'] ?>" class="btn btn-info btn-sm">View</a>
                            <a href="<?= BASE_PATH ?>/students?action=edit&id=<?= $s['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= BASE_PATH ?>/students?action=delete&id=<?= $s['id'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this student?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12" class="text-center">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
