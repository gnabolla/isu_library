<div class="container mt-4">
    <h2>Courses <a href="<?= BASE_PATH ?>/courses?action=create" class="btn btn-primary btn-sm">Add New</a></h2>
    
    <?php if (!empty($courses)): ?>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($courses as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td>
                        <a href="<?= BASE_PATH ?>/courses?action=edit&id=<?= $c['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= BASE_PATH ?>/courses?action=delete&id=<?= $c['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Delete this course?')"
                        >Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No courses found.</p>
    <?php endif; ?>
</div>
