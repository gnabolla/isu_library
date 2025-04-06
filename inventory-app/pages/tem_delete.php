<?php
// File: pages/item_delete.php
// Path: /inventory-app/pages/item_delete.php
// Delete inventory item

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    addFlashMessage('danger', 'Invalid item ID.');
    header('Location: index.php');
    exit;
}

if (deleteItem($conn, $id)) {
    addFlashMessage('success', 'Item deleted successfully!');
} else {
    addFlashMessage('danger', 'Error deleting item.');
}

header('Location: index.php');
exit;