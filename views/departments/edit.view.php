<div class="container mt-4">
    <h2>Edit Department</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Department Name</label>
            <input type="text" name="name" class="form-control" 
                   value="<?= htmlspecialchars($department['name']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= BASE_PATH ?>/departments" class="btn btn-secondary">Cancel</a>
    </form>
</div>