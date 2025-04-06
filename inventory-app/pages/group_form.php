<?php
// File: pages/group_form.php
// Path: /inventory-app/pages/group_form.php
// Form for adding/editing inventory groups

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$isEdit = $id !== null;
$formTitle = $isEdit ? 'Edit Inventory Group' : 'Add New Inventory Group';
$formAction = $isEdit ? "?section=groups&page=edit&id={$id}" : "?section=groups&page=add";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (saveGroup($conn, $_POST)) {
        addFlashMessage('success', $isEdit ? 'Group updated successfully!' : 'Group added successfully!');
        header('Location: index.php?section=groups');
        exit;
    } else {
        addFlashMessage('danger', 'Error saving group. Please try again.');
    }
}

// Get existing data if editing
$groupData = [];
if ($isEdit) {
    $groupData = getGroupById($conn, $id);
    if (!$groupData) {
        addFlashMessage('danger', 'Group not found.');
        header('Location: index.php?section=groups');
        exit;
    }
}
?>

<div class="mb-3">
    <h2><?= $formTitle ?></h2>
    <a href="index.php?section=groups" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Groups</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= $formAction ?>" method="post">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $id ?>">
            <?php endif; ?>
            
            <div class="mb-3">
                <label for="name" class="form-label">Group Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $isEdit ? htmlspecialchars($groupData['name']) : '' ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= $isEdit ? htmlspecialchars($groupData['description']) : '' ?></textarea>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="index.php?section=groups" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Group</button>
            </div>
        </form>
    </div>
</div>