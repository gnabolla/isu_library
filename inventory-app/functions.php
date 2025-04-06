<?php
// File: functions.php
// Path: /inventory-app/functions.php
// Helper functions for the application

// Add flash message
function addFlashMessage($type, $message) {
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message
    ];
}

// Get all inventory groups
function getAllGroups($conn) {
    $groups = [];
    $result = $conn->query("SELECT * FROM inventory_groups ORDER BY id DESC");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $groups[] = $row;
        }
    }
    
    return $groups;
}

// Get a single inventory group by ID
function getGroupById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM inventory_groups WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Save inventory group
function saveGroup($conn, $data) {
    $id = isset($data['id']) ? (int)$data['id'] : null;
    $name = $data['name'];
    $description = $data['description'];
    
    if ($id) {
        // Update existing group
        $stmt = $conn->prepare("UPDATE inventory_groups SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    } else {
        // Create new group
        $stmt = $conn->prepare("INSERT INTO inventory_groups (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }
}

// Delete inventory group
function deleteGroup($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM inventory_groups WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    return $stmt->execute();
}

// Get all inventory items for a specific group
function getItemsByGroupId($conn, $group_id) {
    $items = [];
    $stmt = $conn->prepare("SELECT * FROM inventory_items WHERE group_id = ? ORDER BY item_no ASC");
    $stmt->bind_param("i", $group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    
    return $items;
}

// Get a single inventory item by ID
function getItemById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM inventory_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Save inventory items for a group
function saveItems($conn, $data, $group_id) {
    // First delete all existing items for this group
    $stmt = $conn->prepare("DELETE FROM inventory_items WHERE group_id = ?");
    $stmt->bind_param("i", $group_id);
    if (!$stmt->execute()) {
        return false;
    }
    
    // Insert all items
    $stmt = $conn->prepare("INSERT INTO inventory_items (group_id, item_no, qty, unit, description, original_price, markup, unit_price, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    for ($i = 0; $i < count($data['item_no']); $i++) {
        // Calculate unit price and total
        $unitPrice = $data['original_price'][$i] * (1 + $data['markup'][$i]/100);
        $total = $data['qty'][$i] * $unitPrice;
        
        $stmt->bind_param(
            "iidssdddd",
            $group_id,
            $data['item_no'][$i],
            $data['qty'][$i],
            $data['unit'][$i],
            $data['description'][$i],
            $data['original_price'][$i],
            $data['markup'][$i],
            $unitPrice,
            $total
        );
        
        if (!$stmt->execute()) {
            return false;
        }
    }
    
    return true;
}

// Delete inventory item
function deleteItem($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM inventory_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    return $stmt->execute();
}

// Calculate grand total for a set of items
function calculateGrandTotal($items) {
    $grandTotal = 0;
    
    foreach ($items as $item) {
        $grandTotal += $item['total'];
    }
    
    return $grandTotal;
}

// Count items in a group
function countItemsInGroup($conn, $group_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM inventory_items WHERE group_id = ?");
    $stmt->bind_param("i", $group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['count'];
}