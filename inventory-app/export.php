<?php
// File: export.php
// Path: /inventory-app/export.php
// Export inventory items to Excel

session_start();
include 'config.php';
include 'functions.php';

// Get all items
$items = getAllItems($conn);
$grandTotal = calculateGrandTotal($items);

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="inventory_' . date('Y-m-d') . '.xls"');
header('Pragma: no-cache');
header('Expires: 0');

// Output Excel data
echo '<table border="1">';
// Header row
echo '<tr>';
echo '<th>Item No</th>';
echo '<th>Qty</th>';
echo '<th>Unit</th>';
echo '<th>Description</th>';
echo '<th>Original Price</th>';
echo '<th>Markup (%)</th>';
echo '<th>Unit Price</th>';
echo '<th>Total Amount</th>';
echo '</tr>';

// Data rows
foreach ($items as $item) {
    echo '<tr>';
    echo '<td>' . $item['item_no'] . '</td>';
    echo '<td>' . $item['qty'] . '</td>';
    echo '<td>' . $item['unit'] . '</td>';
    echo '<td>' . $item['description'] . '</td>';
    echo '<td>' . number_format($item['original_price'], 2) . '</td>';
    echo '<td>' . number_format($item['markup'], 2) . '</td>';
    echo '<td>' . number_format($item['unit_price'], 2) . '</td>';
    echo '<td>' . number_format($item['total'], 2) . '</td>';
    echo '</tr>';
}

// Grand total row
echo '<tr>';
echo '<td colspan="7" align="right"><strong>Grand Total:</strong></td>';
echo '<td><strong>' . number_format($grandTotal, 2) . '</strong></td>';
echo '</tr>';

echo '</table>';
exit;