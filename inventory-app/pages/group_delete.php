<?php
// File: pages/group_delete.php
// Path: /inventory-app/pages/group_delete.php
// Delete inventory group and all its items

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    addFlashMessage('danger', 'Invalid group ID.');
    header('Location: index.php?section=groups');
    exit;
}

if (deleteGroup($conn, $id)) {
    addFlashMessage('success', 'Group and all its items deleted successfully!');
} else {
    addFlashMessage('danger', 'Error deleting group.');
}

header('Location: index.php?section=groups');
exit;