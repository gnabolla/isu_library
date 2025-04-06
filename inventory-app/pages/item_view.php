<?php
// File: pages/item_view.php
// Path: /inventory-app/pages/item_view.php
// View details of an inventory item

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    addFlashMessage('danger', 'Invalid item ID.');
    header('Location: index.php');
    exit;
}

$item = getItemById($conn, $id);

if (!$item) {
    addFlashMessage('danger', 'Item not found.');
    header('Location: index.php');
    exit;
}
?>

<div class="mb-3">
    <h2>View Inventory Entry</h2>
    <div>
        <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to List</a>
        <a href="?page=edit&id=<?= $id ?>" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="?page=delete&id=<?= $id ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?')"><i class="bi bi-trash"></i> Delete</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Entry Details</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th width="200">Item No:</th>
                    <td><?= htmlspecialchars($item['item_no']) ?></td>
                </tr>
                <tr>
                    <th>Quantity:</th>
                    <td><?= htmlspecialchars($item['qty']) ?></td>
                </tr>
                <tr>
                    <th>Unit:</th>
                    <td><?= htmlspecialchars($item['unit']) ?></td>
                </tr>
                <tr>
                    <th>Description:</th>
                    <td><?= htmlspecialchars($item['description']) ?></td>
                </tr>
                <tr>
                    <th>Original Price:</th>
                    <td>$<?= number_format($item['original_price'], 2) ?></td>
                </tr>
                <tr>
                    <th>Markup:</th>
                    <td><?= number_format($item['markup'], 2) ?>%</td>
                </tr>
                <tr>
                    <th>Unit Price:</th>
                    <td>$<?= number_format($item['unit_price'], 2) ?></td>
                </tr>
                <tr>
                    <th>Total Amount:</th>
                    <td>$<?= number_format($item['total'], 2) ?></td>
                </tr>
                <tr>
                    <th>Created At:</th>
                    <td><?= htmlspecialchars($item['created_at']) ?></td>
                </tr>
                <tr>
                    <th>Last Updated:</th>
                    <td><?= htmlspecialchars($item['updated_at']) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>