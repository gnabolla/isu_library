<?php

use Core\Middleware;
use PhpOffice\PhpSpreadsheet\IOFactory;

require 'vendor/autoload.php';

Middleware::requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
    try {
        $inputFileName = $_FILES['excelFile']['tmp_name'];
        $spreadsheet = IOFactory::load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        // Remove header row
        array_shift($rows);
        
        $db = Database::getInstance()->getConnection();
        
        foreach ($rows as $row) {
            if (empty($row[0])) continue;
            
            $idno = $row[0];
            $fullname = $row[1];
            $course_section = $row[2];
            $sex = $row[3];
            
            // Parse name (assuming format: "Lastname, Firstname Middlename")
            $name_parts = explode(',', $fullname);
            $lastname = trim($name_parts[0]);
            $firstname_middle = explode(' ', trim($name_parts[1]));
            $firstname = $firstname_middle[0];
            $middlename = count($firstname_middle) > 1 ? end($firstname_middle) : '';
            
            // Extract year and section from course string
            preg_match('/(\d+)([A-Za-z]+)$/', $course_section, $matches);
            $year = $matches[1] ?? 1;
            $section = $matches[2] ?? 'A';
            
            // Extract course name
            $course_name = trim(preg_replace('/\d+[A-Za-z]+$/', '', $course_section));
            
            // Get course_id
            $stmt = $db->prepare('SELECT id FROM courses WHERE name = ?');
            $stmt->execute([$course_name]);
            $course_id = $stmt->fetchColumn() ?: 1; // Default to 1 if not found
            
            // Default department_id (you may want to adjust this logic)
            $department_id = 1;
            
            // Generate temporary RFID (you should replace this with actual RFID values)
            $rfid = $idno;
            
            // Insert student
            $stmt = $db->prepare('INSERT INTO students (firstname, middlename, lastname, year, course_id, section, department_id, rfid, sex) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            
            $stmt->execute([
                $firstname,
                $middlename,
                $lastname,
                $year,
                $course_id,
                $section,
                $department_id,
                $rfid,
                $sex ?: 'Other'
            ]);
        }
        
        header('Location: ' . BASE_PATH . '/setup?success=1');
        exit;
        
    } catch (Exception $e) {
        // Log error and redirect with error message
        error_log($e->getMessage());
        header('Location: ' . BASE_PATH . '/import-students?error=1');
        exit;
    }
}

$title = "Import Students";
$view = "views/import/students.view.php";
require "views/layout.view.php";
