<?php
// File: pages/item_form.php
// Path: /inventory-app/pages/item_form.php
// Form for adding/editing inventory items

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$isEdit = $id !== null;
$formTitle = $isEdit ? 'Edit Inventory Entry' : 'Add New Inventory Entry';
$formAction = $isEdit ? "?page=edit&id={$id}" : "?page=add";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (saveItem($conn, $_POST, $id)) {
        addFlashMessage('success', $isEdit ? 'Entry updated successfully!' : 'Entry added successfully!');
        header('Location: index.php');
        exit;
    } else {
        addFlashMessage('danger', 'Error saving entry. Please try again.');
    }
}

// Get existing data if editing
$itemData = [];
if ($isEdit) {
    $itemData = getItemById($conn, $id);
    if (!$itemData) {
        addFlashMessage('danger', 'Entry not found.');
        header('Location: index.php');
        exit;
    }
}
?>

<div class="mb-3">
    <h2><?= $formTitle ?></h2>
    <a href="index.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to List</a>
</div>

<form action="<?= $formAction ?>" method="post">
    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="item-table" class="table table-bordered">
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($isEdit): ?>
                            <tr class="item-row">
                                <td>
                                    <input type="text" name="item_no[]" class="form-control item-no" value="<?= htmlspecialchars($itemData['item_no']) ?>" readonly>
                                </td>
                                <td>
                                    <input type="number" name="qty[]" class="form-control qty" min="0" step="any" value="<?= htmlspecialchars($itemData['qty']) ?>" required>
                                </td>
                                <td>
                                    <input type="text" name="unit[]" class="form-control" value="<?= htmlspecialchars($itemData['unit']) ?>" required>
                                </td>
                                <td>
                                    <input type="text" name="description[]" class="form-control" value="<?= htmlspecialchars($itemData['description']) ?>" required>
                                </td>
                                <td>
                                    <input type="number" name="original_price[]" class="form-control original-price" min="0" step="any" value="<?= htmlspecialchars($itemData['original_price']) ?>" required>
                                </td>
                                <td>
                                    <input type="number" name="markup[]" class="form-control markup" min="0" step="any" value="<?= htmlspecialchars($itemData['markup']) ?>" required>
                                </td>
                                <td>
                                    <input type="number" name="unit_price[]" class="form-control unit-price" value="<?= htmlspecialchars($itemData['unit_price']) ?>" readonly>
                                </td>
                                <td>
                                    <input type="number" name="total[]" class="form-control total" value="<?= htmlspecialchars($itemData['total']) ?>" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger remove-row"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr class="item-row">
                                <td>
                                    <input type="text" name="item_no[]" class="form-control item-no" value="1" readonly>
                                </td>
                                <td>
                                    <input type="number" name="qty[]" class="form-control qty" min="0" step="any" required>
                                </td>
                                <td>
                                    <input type="text" name="unit[]" class="form-control" required>
                                </td>
                                <td>
                                    <input type="text" name="description[]" class="form-control" required>
                                </td>
                                <td>
                                    <input type="number" name="original_price[]" class="form-control original-price" min="0" step="any" required>
                                </td>
                                <td>
                                    <input type="number" name="markup[]" class="form-control markup" min="0" step="any" required>
                                </td>
                                <td>
                                    <input type="number" name="unit_price[]" class="form-control unit-price" readonly>
                                </td>
                                <td>
                                    <input type="number" name="total[]" class="form-control total" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger remove-row"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="text-end fw-bold">Grand Total:</td>
                            <td>
                                <input type="number" id="grand-total" class="form-control" readonly>
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="mt-3">
                <button type="button" id="add-row" class="btn btn-info"><i class="bi bi-plus-circle"></i> Add Row</button>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-between">
        <a href="index.php" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Entry</button>
    </div>
</form>