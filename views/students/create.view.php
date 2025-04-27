<div class="container mt-4">
    <h2>Add New Student</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_PATH ?>/students?action=create" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="firstname" class="form-label">First Name</label>
            <input type="text" name="firstname" class="form-control" required 
                   value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="middlename" class="form-label">Middle Name</label>
            <input type="text" name="middlename" class="form-control" required
                   value="<?= htmlspecialchars($_POST['middlename'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Last Name</label>
            <input type="text" name="lastname" class="form-control" required 
                   value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <select name="year" class="form-select" required>
                <option value="">Select Year</option>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <option value="<?= $i ?>" 
                        <?= (isset($_POST['year']) && $_POST['year'] == $i) ? 'selected' : '' ?>>
                        <?= $i ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- Course selection -->
        <div class="mb-3">
            <label for="course_id" class="form-label">Course</label>
            <select name="course_id" class="form-select" required>
                <option value="">Select Course</option>
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?= $c['id'] ?>"
                            <?= (isset($_POST['course_id']) && $_POST['course_id'] == $c['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- Section -->
        <div class="mb-3">
            <label for="section" class="form-label">Section</label>
            <input type="text" name="section" class="form-control"
                   value="<?= htmlspecialchars($_POST['section'] ?? '') ?>">
        </div>

        <!-- Department selection -->
        <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select name="department_id" class="form-select" required>
                <option value="">Select Department</option>
                <?php if (!empty($departments)): ?>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>"
                            <?= (isset($_POST['department_id']) && $_POST['department_id'] == $d['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="rfid" class="form-label">Student ID</label>
            <input type="text" name="rfid" class="form-control" required
                   value="<?= htmlspecialchars($_POST['rfid'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="sex" class="form-label">Sex</label>
            <select name="sex" class="form-select" required>
                <option value="">Select Sex</option>
                <option value="Male"   <?= (isset($_POST['sex']) && $_POST['sex'] == 'Male')   ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= (isset($_POST['sex']) && $_POST['sex'] == 'Female') ? 'selected' : '' ?>>Female</option>
                <option value="Other"  <?= (isset($_POST['sex']) && $_POST['sex'] == 'Other')  ? 'selected' : '' ?>>Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Profile Image (Optional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Add Student</button>
        <a href="<?= BASE_PATH ?>/students" class="btn btn-secondary">Cancel</a>
    </form>
</div>
