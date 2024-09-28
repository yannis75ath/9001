<?php
include 'db.php';

// Check if user is an admin
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
    $Doc_ID = $_POST['Doc_ID'];
    $Doc_Category = $_POST['Doc_Category'];
    $Doc_PaperID = $_POST['Doc_PaperID'];
    $Doc_Title = $_POST['Doc_Title'];
    $Doc_Version = $_POST['Doc_Version'];
    $Doc_Date = $_POST['Doc_Date'];

    // Check if a new file is uploaded
    if (isset($_FILES["Doc_File"]) && $_FILES["Doc_File"]["error"] == 0) {
        // Define file paths
        $target_dir = "../uploads/";
        $original_filename = $_FILES["Doc_File"]["name"];
        $target_file = $target_dir . basename($original_filename);
        
        $Doc_FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = array("pdf", "docx", "xlsx");

        if (in_array($Doc_FileType, $allowed_types)) {
            // Overwrite the existing file with the new one
            if (move_uploaded_file($_FILES["Doc_File"]["tmp_name"], $target_file)) {
                // Update the database with new file information
                $stmt = $conn->prepare("UPDATE diadikasies SET Doc_Category = ?, Doc_PaperID = ?, Doc_Title = ?, Doc_Version = ?, Doc_Date = ?, Doc_File = ?, Doc_FileType = ? WHERE Doc_ID = ?");
                $stmt->bind_param("sssssssi", 
                    $Doc_Category, 
                    $Doc_PaperID, 
                    $Doc_Title, 
                    $Doc_Version, 
                    $Doc_Date, 
                    $target_file, 
                    $Doc_FileType, 
                    $Doc_ID
                );

                if ($stmt->execute()) {
                    echo "Το έγγραφο ενημερώθηκε επιτυχώς!";
                } else {
                    echo "Σφάλμα κατά την ενημέρωση του εγγράφου: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Υπήρξε πρόβλημα κατά το ανέβασμα του αρχείου.";
            }
        } else {
            echo "Μη επιτρεπόμενος τύπος αρχείου.";
        }
    } else {
        // If no new file is uploaded, update only other fields
        $stmt = $conn->prepare("UPDATE diadikasies SET Doc_Category = ?, Doc_PaperID = ?, Doc_Title = ?, Doc_Version = ?, Doc_Date = ? WHERE Doc_ID = ?");
        $stmt->bind_param("sssssi", 
            $Doc_Category, 
            $Doc_PaperID, 
            $Doc_Title, 
            $Doc_Version, 
            $Doc_Date, 
            $Doc_ID
        );

        if ($stmt->execute()) {
            echo "Οι αλλαγές αποθηκεύτηκαν επιτυχώς!";
        } else {
            echo "Σφάλμα κατά την αποθήκευση των αλλαγών: " . $stmt->error;
        }
        $stmt->close();
    }

    // Display success message and link back to edit page
    echo "<br><br><a href='edit_diadikasies.php'>Επιστροφή στη λίστα επεξεργασίας</a>";
}

// Close the database connection
$conn->close();
?>