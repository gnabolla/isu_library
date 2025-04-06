<?php
// controllers/import_sarias.php

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
        
        $config = require('config.php');
        $db = new Database($config['database']);
        
        // Get all courses for matching
        $courses = $db->query('SELECT id, name FROM courses')->fetchAll();
        $courseMap = [];
        foreach ($courses as $course) {
            // Map common course acronyms and variations to their IDs
            $courseMap[strtoupper($course['name'])] = $course['id'];
        }
        
        // Get all departments for matching
        $departments = $db->query('SELECT id, name FROM departments')->fetchAll();
        $deptMap = [];
        foreach ($departments as $dept) {
            $deptMap[strtoupper($dept['name'])] = $dept['id'];
        }
        
        // Default department ID if no match is found
        $defaultDeptId = count($departments) > 0 ? $departments[0]['id'] : 1;
        
        // Process rows from SARIAS format
        $studentsAdded = 0;
        $studentsSkipped = 0;
        
        foreach ($rows as $row) {
            // Skip empty rows
            if (empty($row[0]) || count($row) < 3) continue;
            
            try {
                // Extract data from SARIAS format
                // Note: Column order might be different than SACARIAS
                // Assuming columns may be: ID, Name, Program, Section, etc.
                $studentId = trim((string)$row[0]);  // Student ID or RFID
                
                // Make sure studentId is not empty
                if (empty($studentId)) continue;
                
                // For SARIAS, the name format might be different
                $fullname = trim((string)$row[1]);
                $program = isset($row[2]) ? trim((string)$row[2]) : '';
                $section = isset($row[3]) ? trim((string)$row[3]) : '';
                $sex = isset($row[4]) ? trim((string)$row[4]) : '';
                
                // Parse name (try to handle various formats)
                $firstname = '';
                $middlename = '';
                $lastname = '';
                
                if (strpos($fullname, ',') !== false) {
                    // Format: "Last, First Middle"
                    $nameParts = explode(',', $fullname);
                    $lastname = trim($nameParts[0]);
                    if (isset($nameParts[1])) {
                        $firstMiddle = trim($nameParts[1]);
                        $firstMiddleParts = explode(' ', $firstMiddle, 2);
                        $firstname = $firstMiddleParts[0];
                        $middlename = count($firstMiddleParts) > 1 ? $firstMiddleParts[1] : '';
                    }
                } else {
                    // Format: "First Middle Last"
                    $nameParts = explode(' ', $fullname);
                    if (count($nameParts) >= 3) {
                        $firstname = $nameParts[0];
                        $lastname = array_pop($nameParts);
                        array_shift($nameParts); // Remove first name which we already handled
                        $middlename = implode(' ', $nameParts);
                    } elseif (count($nameParts) == 2) {
                        $firstname = $nameParts[0];
                        $lastname = $nameParts[1];
                        $middlename = '';
                    } else {
                        $firstname = $fullname;
                        $lastname = '';
                        $middlename = '';
                    }
                }
                
                // Extract year from program or section
                $year = 1; // Default
                if (!empty($section)) {
                    // Try to extract year from section (e.g. "3A" or "Year 3-A")
                    if (preg_match('/(\d+)[A-Za-z-]/', $section, $matches)) {
                        $year = (int)$matches[1];
                    }
                }
                
                // Try to determine course_id from program
                $course_id = 1; // Default
                if (!empty($program)) {
                    $programUpper = strtoupper($program);
                    if (isset($courseMap[$programUpper])) {
                        $course_id = $courseMap[$programUpper];
                    } else {
                        // Try to find partial match
                        foreach ($courseMap as $key => $id) {
                            if (strpos($programUpper, $key) !== false || strpos($key, $programUpper) !== false) {
                                $course_id = $id;
                                break;
                            }
                        }
                    }
                }
                
                // Format section properly (if it contains year info like "3A", extract just the section part)
                if (preg_match('/\d+([A-Za-z-]+)/', $section, $matches)) {
                    $section = $matches[1];
                }
                
                // Standardize sex value
                if (!empty($sex)) {
                    if (strtolower(substr($sex, 0, 1)) === 'm') {
                        $sex = 'Male';
                    } elseif (strtolower(substr($sex, 0, 1)) === 'f') {
                        $sex = 'Female';
                    } else {
                        $sex = 'Other';
                    }
                } else {
                    $sex = 'Other';
                }
                
                // Check if student with this RFID already exists
                $existingStudent = $db->query('SELECT id FROM students WHERE rfid = ?', [$studentId])->fetch();
                
                if ($existingStudent) {
                    // Skip this student
                    $studentsSkipped++;
                    continue;
                }
                
                // Proceed with insertion
                $stmt = $db->query('INSERT INTO students (
                    firstname, middlename, lastname, year, course_id, 
                    section, department_id, rfid, sex, image
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                    $firstname,
                    $middlename,
                    $lastname,
                    $year,
                    $course_id,
                    $section,
                    $defaultDeptId,
                    $studentId,
                    $sex,
                    'assets/img/default-avatar.png'  // Default image
                ]);
                
                $studentsAdded++;
                
            } catch (Exception $e) {
                // Log individual row errors but continue processing
                error_log('Error processing SARIAS row: ' . json_encode($row) . '. Error: ' . $e->getMessage());
                continue;
            }
        }
        
        // Redirect with success message
        header('Location: ' . BASE_PATH . '/import-students?success=1&added=' . $studentsAdded . '&skipped=' . $studentsSkipped);
        exit;
        
    } catch (Exception $e) {
        // Log error and redirect with error message
        error_log('SARIAS Import Error: ' . $e->getMessage());
        header('Location: ' . BASE_PATH . '/import-students?error=' . urlencode($e->getMessage()));
        exit;
    }
}

// If not a POST request or no file uploaded, redirect back
header('Location: ' . BASE_PATH . '/import-students');
exit;