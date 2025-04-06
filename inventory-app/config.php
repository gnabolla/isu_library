<?php
// File: config.php
// Path: /inventory-app/config.php
// Database configuration and connection

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Change to your MySQL username
define('DB_PASS', ''); // Change to your MySQL password
define('DB_NAME', 'inventory_app');

// Create connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Check if database exists, if not create it
    $result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DB_NAME . "'");
    
    if ($result->num_rows == 0) {
        // Create database
        $sql = "CREATE DATABASE " . DB_NAME;
        if ($conn->query($sql) !== TRUE) {
            throw new Exception("Error creating database: " . $conn->error);
        }
    }
    
    // Select the database
    $conn->select_db(DB_NAME);
    
    // Check if inventory_groups table exists, if not create it
    $result = $conn->query("SHOW TABLES LIKE 'inventory_groups'");
    
    if ($result->num_rows == 0) {
        // Create inventory_groups table
        $sql = "CREATE TABLE inventory_groups (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        if ($conn->query($sql) !== TRUE) {
            throw new Exception("Error creating inventory_groups table: " . $conn->error);
        }
    }
    
    // Check if inventory_items table exists, if not create it
    $result = $conn->query("SHOW TABLES LIKE 'inventory_items'");
    
    if ($result->num_rows == 0) {
        // Create inventory_items table with group_id foreign key
        $sql = "CREATE TABLE inventory_items (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            group_id INT(11) UNSIGNED NOT NULL,
            item_no INT(11) NOT NULL,
            qty DECIMAL(10,2) NOT NULL,
            unit VARCHAR(50) NOT NULL,
            description VARCHAR(255) NOT NULL,
            original_price DECIMAL(10,2) NOT NULL,
            markup DECIMAL(10,2) NOT NULL,
            unit_price DECIMAL(10,2) NOT NULL,
            total DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (group_id) REFERENCES inventory_groups(id) ON DELETE CASCADE
        )";
        
        if ($conn->query($sql) !== TRUE) {
            throw new Exception("Error creating inventory_items table: " . $conn->error);
        }
    } else {
        // Check if the group_id column exists in the inventory_items table
        $result = $conn->query("SHOW COLUMNS FROM inventory_items LIKE 'group_id'");
        
        if ($result->num_rows == 0) {
            // Alter the table to add the group_id column
            $sql = "ALTER TABLE inventory_items 
                    ADD COLUMN group_id INT(11) UNSIGNED NOT NULL AFTER id,
                    ADD FOREIGN KEY (group_id) REFERENCES inventory_groups(id) ON DELETE CASCADE";
            
            if ($conn->query($sql) !== TRUE) {
                throw new Exception("Error updating inventory_items table: " . $conn->error);
            }
        }
    }
    
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}