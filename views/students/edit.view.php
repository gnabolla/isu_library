<!-- views/students/edit.view.php -->

<div class="container mt-4">
    <h2>Edit Student</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_PATH ?>/students?action=edit&id=<?= $student['id'] ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="firstname" class="form-label">First Name</label>
            <input type="text" name="firstname" class="form-control" required 
                   value="<?= htmlspecialchars($_POST['firstname'] ?? $student['firstname']) ?>">
        </div>
        <div class="mb-3">
            <label for="middlename" class="form-label">Middle Name</label>
            <input type="text" name="middlename" class="form-control" required
                   value="<?= htmlspecialchars($_POST['middlename'] ?? $student['middlename']) ?>">
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Last Name</label>
            <input type="text" name="lastname" class="form-control" required
                   value="<?= htmlspecialchars($_POST['lastname'] ?? $student['lastname']) ?>">
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <select name="year" class="form-select" required>
                <option value="">Select Year</option>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <option value="<?= $i ?>" 
                        <?= (
                            (isset($_POST['year']) && $_POST['year'] == $i) ||
                            $student['year'] == $i
                        ) ? 'selected' : '' ?>>
                        Year <?= $i ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="course" class="form-label">Course</label>
            <input type="text" name="course" class="form-control" required 
                   value="<?= htmlspecialchars($_POST['course'] ?? $student['course']) ?>">
        </div>
        <div class="mb-3">
            <label for="section" class="form-label">Section</label>
            <input type="text" name="section" class="form-control" required
                   value="<?= htmlspecialchars($_POST['section'] ?? $student['section']) ?>">
        </div>
        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <input type="text" name="department" class="form-control" required
                   value="<?= htmlspecialchars($_POST['department'] ?? $student['department']) ?>">
        </div>
        <div class="mb-3">
            <label for="rfid" class="form-label">RFID</label>
            <input type="text" name="rfid" class="form-control" required
                   value="<?= htmlspecialchars($_POST['rfid'] ?? $student['rfid']) ?>">
        </div>
        <div class="mb-3">
            <label for="sex" class="form-label">Sex</label>
            <select name="sex" class="form-select" required>
                <option value="">Select Sex</option>
                <option value="Male"   <?= (
                    (isset($_POST['sex']) && $_POST['sex'] == 'Male') ||
                     $student['sex'] == 'Male'
                ) ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= (
                    (isset($_POST['sex']) && $_POST['sex'] == 'Female') ||
                     $student['sex'] == 'Female'
                ) ? 'selected' : '' ?>>Female</option>
                <option value="Other"  <?= (
                    (isset($_POST['sex']) && $_POST['sex'] == 'Other') ||
                     $student['sex'] == 'Other'
                ) ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Profile Image (Optional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <?php if ($student['image'] && file_exists($student['image'])): ?>
                <img src="<?= BASE_PATH ?>/<?= htmlspecialchars($student['image']) ?>" alt="Current Image" width="100" class="mt-2">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update Student</button>
        <a href="<?= BASE_PATH ?>/students" class="btn btn-secondary">Cancel</a>
    </form>
</div>
