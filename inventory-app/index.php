<?php
// File: index.php
// Path: /inventory-app/index.php
// Main application entry point

session_start();
include 'config.php';
include 'functions.php';

// Initialize flash messages if not set
if (!isset($_SESSION['flash_messages'])) {
    $_SESSION['flash_messages'] = [];
}

$section = isset($_GET['section']) ? $_GET['section'] : 'groups';
$page = isset($_GET['page']) ? $_GET['page'] : 'list';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        .container { max-width: 1200px; }
        .table th, .table td { vertical-align: middle; }
        .nav-tabs { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Inventory Management System</h1>
        
        <!-- Navigation tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?= ($section == 'groups') ? 'active' : '' ?>" href="index.php?section=groups">Inventory Groups</a>
            </li>
            <?php if ($section == 'items' && isset($_GET['group_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link active" href="index.php?section=items&group_id=<?= $_GET['group_id'] ?>">
                        Items for <?= htmlspecialchars(getGroupById($conn, $_GET['group_id'])['name']) ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
        
        <?php
        // Display flash messages
        if (!empty($_SESSION['flash_messages'])) {
            foreach ($_SESSION['flash_messages'] as $message) {
                echo "<div class='alert alert-{$message['type']} alert-dismissible fade show' role='alert'>
                    {$message['message']}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
            }
            // Clear flash messages
            $_SESSION['flash_messages'] = [];
        }
        
        // Load appropriate section and page based on request
        if ($section == 'groups') {
            switch ($page) {
                case 'add':
                    include 'pages/group_form.php';
                    break;
                case 'edit':
                    include 'pages/group_form.php';
                    break;
                case 'delete':
                    include 'pages/group_delete.php';
                    break;
                default:
                    include 'pages/group_list.php';
                    break;
            }
        } else if ($section == 'items' && isset($_GET['group_id'])) {
            $group_id = (int)$_GET['group_id'];
            switch ($page) {
                case 'manage':
                    include 'pages/item_form.php';
                    break;
                case 'export':
                    // This will be handled by export.php
                    header("Location: export.php?group_id={$group_id}");
                    exit;
                default:
                    include 'pages/item_list.php';
                    break;
            }
        } else {
            // Default to group list
            include 'pages/group_list.php';
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script>
        // Calculate row total when unit price or quantity changes
        $(document).on('input', '.qty, .original-price, .markup', function() {
            const row = $(this).closest('tr');
            calculateRowTotal(row);
            calculateGrandTotal();
        });

        function calculateRowTotal(row) {
            const qty = parseFloat(row.find('.qty').val()) || 0;
            const originalPrice = parseFloat(row.find('.original-price').val()) || 0;
            const markup = parseFloat(row.find('.markup').val()) || 0;
            
            const unitPrice = originalPrice * (1 + markup/100);
            const total = qty * unitPrice;
            
            row.find('.unit-price').val(unitPrice.toFixed(2));
            row.find('.total').val(total.toFixed(2));
        }

        function calculateGrandTotal() {
            let grandTotal = 0;
            $('.total').each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });
            $('#grand-total').val(grandTotal.toFixed(2));
        }

        // Add row button functionality
        $('#add-row').click(function() {
            const rowNum = $('.item-row').length + 1;
            const newRow = `
                <tr class="item-row">
                    <td>
                        <input type="text" name="item_no[]" class="form-control item-no" value="${rowNum}" readonly>
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
            `;
            $('#item-table tbody').append(newRow);
        });

        // Remove row button functionality
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            // Renumber rows
            $('.item-row').each(function(index) {
                $(this).find('.item-no').val(index + 1);
            });
            calculateGrandTotal();
        });
    </script>
</body>
</html>