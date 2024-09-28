<?php
include 'db.php';

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Validate CSRF Token
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("Invalid CSRF token");
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $Doc_Category = $_POST['Doc_Category'];
    $Doc_PaperID = $_POST['Doc_PaperID'];
    $Doc_Title = $_POST['Doc_Title'];
    $Doc_Version = $_POST['Doc_Version'];
    $Doc_Date = $_POST['Doc_Date'];

    // File upload handling
    $target_dir_temp = "../uploads/temp/";
    if (!file_exists($target_dir_temp)) {
        mkdir($target_dir_temp, 0755, true);
    }

    $original_filename = $_FILES["Doc_File"]["name"];
    $temp_target_file = $target_dir_temp . basename($original_filename); // Using original filename in temp directory

    $Doc_FileType = strtolower(pathinfo($temp_target_file, PATHINFO_EXTENSION));
    $allowed_types = array("pdf", "docx", "xlsx");

    if (in_array($Doc_FileType, $allowed_types)) {
        if (move_uploaded_file($_FILES["Doc_File"]["tmp_name"], $temp_target_file)) {

            // Insert form data into the database and get the new Doc_ID
            $stmt = $conn->prepare("INSERT INTO diadikasies (Doc_Category, Doc_PaperID, Doc_Title, Doc_Version, Doc_Date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $Doc_Category, $Doc_PaperID, $Doc_Title, $Doc_Version, $Doc_Date);

            if ($stmt->execute()) {
                // Get the newly created Doc_ID
                $Doc_ID = $stmt->insert_id; // This gets the last inserted ID
                
                // Store original filename, Doc_ID, and form data in session for confirmation step
                $_SESSION['original_filename'] = $original_filename;
                $_SESSION['form_data'] = [
                    'Doc_Category' => $Doc_Category,
                    'Doc_PaperID' => $Doc_PaperID,
                    'Doc_Title' => $Doc_Title,
                    'Doc_Version' => $Doc_Version,
                    'Doc_Date' => $Doc_Date,
                    'Doc_ID' => $Doc_ID // Store the new Doc_ID in the session
                ];

                // Redirect to confirmation page
                header("Location: add_diadikasia_to_db_confirm_upload.php");
                exit();
            } else {
                echo "Υπήρξε πρόβλημα κατά την αποθήκευση των δεδομένων στη βάση.";
            }

            $stmt->close();
        } else {
            echo "Υπήρξε πρόβλημα κατά το ανέβασμα του αρχείου.";
        }
    } else {
        echo "Μη επιτρεπόμενος τύπος αρχείου.";
    }
}
?>
