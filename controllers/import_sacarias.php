<?php
// controllers/import_sacarias.php

use Core\Middleware;
use PhpOffice\PhpSpreadsheet\IOFactory;

require 'vendor/autoload.php';

Middleware::requireAuth();

// Debug mode - create a log file to trace the import process
$debug = true;
$logFile = 'sacarias_import_log.txt';

function logDebug($message) {
    global $debug, $logFile;
    if ($debug) {
        file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $message . "\n", FILE_APPEND);
    }
}

logDebug("Starting SACARIAS import process");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
    try {
        logDebug("POST request received with file upload");
        logDebug("File info: " . json_encode($_FILES['excelFile']));
        
        // Check for upload errors
        if ($_FILES['excelFile']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload error: " . $_FILES['excelFile']['error']);
        }
        
        $inputFileName = $_FILES['excelFile']['tmp_name'];
        logDebug("Loading file: " . $inputFileName);
        
        // Check if file exists and is readable
        if (!file_exists($inputFileName) || !is_readable($inputFileName)) {
            throw new Exception("Cannot read uploaded file: " . $inputFileName);
        }
        
        // Use the auto-detect file type feature of PHPSpreadsheet
        $fileType = IOFactory::identify($inputFileName);
        logDebug("Detected file type: " . $fileType);
        
        $reader = IOFactory::createReader($fileType);
        $reader->setReadDataOnly(true); // Only read cell data, not formatting
        
        $spreadsheet = $reader->load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        logDebug("Excel file loaded successfully, worksheet: " . $worksheet->getTitle());
        
        // Log more details about the Excel structure
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        logDebug("Excel dimensions: " . $highestColumn . $highestRow);
        
        // Get column headers
        $headers = [];
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $cell = $worksheet->getCellByColumnAndRow($col, 1);
            $headers[$col-1] = $cell->getValue();
        }
        logDebug("Column headers: " . json_encode($headers));
        
        // Convert to array with numeric indices
        $rows = $worksheet->toArray();
        logDebug("Total rows (including header): " . count($rows));
        
        // Remove header row
        array_shift($rows);
        logDebug("Header row removed, remaining rows: " . count($rows));
        
        // Log a few sample rows to verify format
        for ($i = 0; $i < min(3, count($rows)); $i++) {
            logDebug("Sample row " . ($i+1) . ": " . json_encode($rows[$i]));
        }
        
        $config = require('config.php');
        logDebug("Database config: " . json_encode($config['database']));
        
        $db = new Database($config['database']);
        $pdo = $db->getConnection();
        
        // Verify database connection
        try {
            $stmt = $pdo->query("SELECT DATABASE() as current_db");
            $dbInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            logDebug("Connected to database: " . json_encode($dbInfo));
            
            // Count existing students
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM students");
            $countInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            logDebug("Current student count in database: " . $countInfo['count']);
            
            // Check database structure
            $stmt = $pdo->query("DESCRIBE students");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            logDebug("Student table structure: " . json_encode($columns));
        } catch (Exception $e) {
            logDebug("Error checking database: " . $e->getMessage());
        }
        
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
        
        // Log all courses and departments for debugging
        logDebug("Available courses: " . json_encode($courseMap));
        logDebug("Available departments: " . json_encode($deptMap));
        logDebug("Default department ID: " . $defaultDeptId);
        
        // Process rows from SACARIAS format
        $studentsAdded = 0;
        $studentsSkipped = 0;
        
        foreach ($rows as $index => $row) {
            logDebug("Processing row #" . ($index + 1));
            
            // Skip empty rows
            if (empty($row[0]) || empty($row[1])) {
                logDebug("Skipping empty row");
                continue;
            }
            
            try {
                // Extract data from SACARIAS format
                // Log the entire row for debugging
                logDebug("Raw row data: " . json_encode($row));
                
                // Assuming columns: Student ID, Full Name, Course-Year-Section, Sex, etc.
                $studentId = isset($row[0]) ? trim((string)$row[0]) : '';  // Student ID or RFID
                $fullname = isset($row[1]) ? trim((string)$row[1]) : '';   // Full Name
                $courseYearSection = isset($row[2]) ? trim((string)$row[2]) : '';
                $sex = isset($row[3]) ? trim((string)$row[3]) : '';
                
                logDebug("Extracted: ID='{$studentId}', Name='{$fullname}', Course='{$courseYearSection}', Sex='{$sex}'");
                
                // Parse the name (assuming "Last, First Middle" format)
                $nameParts = explode(',', $fullname);
                if (count($nameParts) >= 2) {
                    $lastname = trim($nameParts[0]);
                    $firstMiddle = trim($nameParts[1]);
                    $firstMiddleParts = explode(' ', $firstMiddle, 2);
                    $firstname = $firstMiddleParts[0];
                    $middlename = count($firstMiddleParts) > 1 ? $firstMiddleParts[1] : '';
                } else {
                    // Fallback if name is not in expected format
                    $nameParts = explode(' ', $fullname);
                    $lastname = array_pop($nameParts);
                    $firstname = array_shift($nameParts) ?? '';
                    $middlename = implode(' ', $nameParts);
                }
                
                // Extract Year and Section from course string (e.g., "BSIT 3A")
                $year = 1;  // Default
                $section = 'A'; // Default
                $courseName = '';
                
                if (!empty($courseYearSection)) {
                    // Try to match pattern like "BSIT 3A" or "BSIT3A"
                    if (preg_match('/([A-Za-z]+)\s*(\d+)([A-Za-z]*)/', $courseYearSection, $matches)) {
                        $courseName = trim($matches[1]);
                        $year = (int)$matches[2];
                        $section = empty($matches[3]) ? 'A' : $matches[3];
                    } else {
                        // If no pattern match, use the whole string as course name
                        $courseName = $courseYearSection;
                    }
                }
                
                // Try to find course_id
                $course_id = 1; // Default
                if (!empty($courseName)) {
                    $courseKey = strtoupper($courseName);
                    if (isset($courseMap[$courseKey])) {
                        $course_id = $courseMap[$courseKey];
                    } else {
                        // Try to find a partial match
                        foreach ($courseMap as $key => $id) {
                            if (strpos($key, $courseKey) !== false || strpos($courseKey, $key) !== false) {
                                $course_id = $id;
                                break;
                            }
                        }
                    }
                }
                
                // Determine department_id (simplified for now)
                $department_id = $defaultDeptId;  // Default to first department
                
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
                
                logDebug("Parsed data: " . json_encode([
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'lastname' => $lastname,
                    'year' => $year,
                    'course_id' => $course_id,
                    'section' => $section,
                    'department_id' => $department_id,
                    'rfid' => $studentId,
                    'sex' => $sex,
                ]));
                
                // Skip if studentId is empty
                if (empty($studentId)) {
                    logDebug("Empty student ID/RFID, will generate one automatically");
                }
                
                // Detailed check of student existence by RFID
                try {
                    $pdo = $db->getConnection();
                    $checkStmt = $pdo->prepare('SELECT id FROM students WHERE rfid = ?');
                    $checkStmt->execute([$studentId]);
                    $existingStudent = $checkStmt->fetch(PDO::FETCH_ASSOC);
                    
                    logDebug("Checking if student exists with RFID '{$studentId}' - Result: " . 
                        ($existingStudent ? "Found (ID: {$existingStudent['id']})" : "Not found"));
                    
                    // Only consider exact RFID matches as existing
                    if ($existingStudent && !empty($studentId)) {
                        logDebug("Student with RFID '{$studentId}' already exists (ID: {$existingStudent['id']}), skipping");
                        $studentsSkipped++;
                        continue;
                    }
                } catch (Exception $e) {
                    logDebug("Error checking existing student: " . $e->getMessage());
                }
                
                logDebug("Inserting new student with RFID: {$studentId}");
                
                try {
                    // Fix for empty RFID - if RFID is empty, we'll generate a unique one
                    if (empty($studentId) || $studentId === '') {
                        $studentId = 'AUTO-' . uniqid();
                        logDebug("Generated automatic RFID: {$studentId}");
                    }
                    
                    // Make sure the values are appropriate for the database schema
                    // Trim all strings to avoid issues with trailing whitespace
                    $firstname = trim($firstname);
                    if (empty($firstname)) $firstname = "Unknown";
                    
                    $middlename = trim($middlename);
                    if (empty($middlename)) $middlename = " ";
                    
                    $lastname = trim($lastname);
                    if (empty($lastname)) $lastname = "Unknown";
                    
                    $section = trim($section);
                    if (empty($section)) $section = "A";
                    
                    $rfid = trim($studentId);
                    
                    // Fix year - ensure it's a valid integer
                    $year = is_numeric($year) ? (int)$year : 1;
                    
                    // Fix course_id and department_id - must be valid integers greater than 0
                    $course_id = is_numeric($course_id) && (int)$course_id > 0 ? (int)$course_id : 1;
                    $department_id = is_numeric($department_id) && (int)$department_id > 0 ? (int)$department_id : $defaultDeptId;
                    
                    logDebug("Sanitized values:");
                    logDebug("firstname: '{$firstname}', middlename: '{$middlename}', lastname: '{$lastname}'");
                    logDebug("year: {$year}, course_id: {$course_id}, section: '{$section}'");
                    logDebug("department_id: {$department_id}, rfid: '{$rfid}', sex: '{$sex}'");
                    
                    // IMPORTANT: Try direct execution bypassing the Database wrapper
                    try {
                        // Execute directly with PDO
                        $sql = 'INSERT INTO students 
                                (firstname, middlename, lastname, year, course_id, 
                                section, department_id, rfid, sex, image)
                                VALUES 
                                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                        
                        $params = [
                            $firstname,
                            $middlename,
                            $lastname,
                            $year,
                            $course_id,
                            $section,
                            $department_id,
                            $rfid,
                            $sex,
                            'assets/img/default-avatar.png'  // Default image
                        ];
                        
                        logDebug("SQL: " . $sql);
                        logDebug("Params: " . json_encode($params));
                        
                        // Get direct PDO connection to try bypass any potential wrapper issues
                        $pdo = $db->getConnection();
                        $stmt = $pdo->prepare($sql);
                        $result = $stmt->execute($params);
                        
                        if ($result) {
                            $newId = $pdo->lastInsertId();
                            logDebug("SUCCESS! Student inserted successfully. Last insert ID: " . $newId);
                            $studentsAdded++;
                        } else {
                            $errorInfo = $stmt->errorInfo();
                            logDebug("Insert failed. PDO Error Info: " . json_encode($errorInfo));
                            throw new Exception("Database insert failed: " . json_encode($errorInfo));
                        }
                    } catch (PDOException $pdoEx) {
                        logDebug("PDO Exception: " . $pdoEx->getMessage());
                        logDebug("PDO Error Code: " . $pdoEx->getCode());
                        
                        // Special handling for duplicate key errors (MySQL error 1062)
                        if ($pdoEx->getCode() == '23000' || strpos($pdoEx->getMessage(), 'Duplicate entry') !== false) {
                            logDebug("Duplicate key detected. This is likely a duplicate RFID.");
                            $studentsSkipped++;
                        } else {
                            throw $pdoEx; // Re-throw for general error handling
                        }
                    }
                } catch (Exception $e) {
                    logDebug("Error inserting student: " . $e->getMessage());
                    // Log the full exception details
                    logDebug("Exception trace: " . $e->getTraceAsString());
                    // Continue processing other records instead of stopping
                    $studentsSkipped++;
                }
                
            } catch (Exception $e) {
                // Log individual row errors but continue processing
                error_log('Error processing SACARIAS row: ' . json_encode($row) . '. Error: ' . $e->getMessage());
                continue;
            }
        }
        
        logDebug("Import completed. Added: {$studentsAdded}, Skipped: {$studentsSkipped}");
        
        // Redirect with success message
        header('Location: ' . BASE_PATH . '/import-students?success=1&added=' . $studentsAdded . '&skipped=' . $studentsSkipped);
        exit;
        
    } catch (Exception $e) {
        // Log error and redirect with error message
        $errorMsg = 'SACARIAS Import Error: ' . $e->getMessage();
        error_log($errorMsg);
        logDebug($errorMsg);
        header('Location: ' . BASE_PATH . '/import-students?error=' . urlencode($e->getMessage()));
        exit;
    }
}

// If not a POST request or no file uploaded, redirect back
header('Location: ' . BASE_PATH . '/import-students');
exit;