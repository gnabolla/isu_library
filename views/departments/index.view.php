<div class="container mt-4">
    <h2>Departments <a href="<?= BASE_PATH ?>/departments?action=create" class="btn btn-primary btn-sm">Add New</a></h2>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($departments as $dept): ?>
            <tr>
                <td><?= $dept['id'] ?></td>
                <td><?= htmlspecialchars($dept['name']) ?></td>
                <td>
                    <a href="<?= BASE_PATH ?>/departments?action=edit&id=<?= $dept['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="<?= BASE_PATH ?>/departments?action=delete&id=<?= $dept['id'] ?>" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Delete this department?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>