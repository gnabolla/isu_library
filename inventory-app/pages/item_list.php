<?php
// File: pages/item_list.php
// Path: /inventory-app/pages/item_list.php
// List all inventory items for a specific group

$group_id = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;

if (!$group_id) {
    addFlashMessage('danger', 'Invalid group ID.');
    header('Location: index.php?section=groups');
    exit;
}

$group = getGroupById($conn, $group_id);
if (!$group) {
    addFlashMessage('danger', 'Group not found.');
    header('Location: index.php?section=groups');
    exit;
}

$items = getItemsByGroupId($conn, $group_id);
$grandTotal = calculateGrandTotal($items);
?>

<div class="d-flex justify-content-between mb-3">
    <h2>Items for <?= htmlspecialchars($group['name']) ?></h2>
    <div>
        <a href="?section=items&page=manage&group_id=<?= $group_id ?>" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Manage Items</a>
        <a href="?section=items&page=export&group_id=<?= $group_id ?>" class="btn btn-success"><i class="bi bi-file-excel"></i> Export to Excel</a>
        <a href="index.php?section=groups" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Groups</a>
    </div>
</div>

<?php if (empty($items)): ?>
    <div class="alert alert-info">
        No items found for this group. <a href="?section=items&page=manage&group_id=<?= $group_id ?>">Add items</a>.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Item No</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Description</th>
                    <th>Original Price</th>
                    <th>Markup (%)</th>
                    <th>Unit Price</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['item_no']) ?></td>
                        <td><?= htmlspecialchars($item['qty']) ?></td>
                        <td><?= htmlspecialchars($item['unit']) ?></td>
                        <td><?= htmlspecialchars($item['description']) ?></td>
                        <td>$<?= number_format($item['original_price'], 2) ?></td>
                        <td><?= number_format($item['markup'], 2) ?>%</td>
                        <td>$<?= number_format($item['unit_price'], 2) ?></td>
                        <td>$<?= number_format($item['total'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-secondary">
                    <td colspan="7" class="text-end fw-bold">Grand Total:</td>
                    <td class="fw-bold">$<?= number_format($grandTotal, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
<?php endif; ?>