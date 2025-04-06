<?php
// File: pages/group_list.php
// Path: /inventory-app/pages/group_list.php
// List all inventory groups

$groups = getAllGroups($conn);
?>

<div class="d-flex justify-content-between mb-3">
    <h2>Inventory Groups</h2>
    <div>
        <a href="?section=groups&page=add" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Group</a>
    </div>
</div>

<?php if (empty($groups)): ?>
    <div class="alert alert-info">
        No inventory groups found. <a href="?section=groups&page=add">Add your first group</a>.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Items</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groups as $index => $group): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($group['name']) ?></td>
                        <td><?= htmlspecialchars($group['description']) ?></td>
                        <td>
                            <?php $itemCount = countItemsInGroup($conn, $group['id']); ?>
                            <?= $itemCount ?> item(s)
                        </td>
                        <td><?= date('M d, Y', strtotime($group['created_at'])) ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="?section=items&group_id=<?= $group['id'] ?>" class="btn btn-info"><i class="bi bi-list-ul"></i> View Items</a>
                                <a href="?section=groups&page=edit&id=<?= $group['id'] ?>" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
                                <a href="?section=groups&page=delete&id=<?= $group['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this group and all its items?')"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>