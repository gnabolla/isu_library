<div class="container mt-4">
    <h2>Edit Course</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Course Name</label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($course['name']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= BASE_PATH ?>/courses" class="btn btn-secondary">Cancel</a>
    </form>
</div>
