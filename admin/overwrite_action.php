<?php
include 'db.php';

// Check for CSRF Token Security
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("Invalid CSRF token");
}

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Post Bracket";
    if ($_POST['overwrite'] == 'yes') {
        echo "Overwrite Bracket";
        // Check if file is uploaded before trying to access it
        if (isset($_FILES['Doc_File']) && $_FILES['Doc_File']['error'] == 0) {
            echo "DocFile Error Bracket";

            $file = $_POST['file'];
            $Doc_Category = $_POST['category'];
            $Doc_PaperID = $_POST['paperid'];
            $Doc_Title = $_POST['title'];
            $Doc_Version = $_POST['version'];
            $Doc_Date = $_POST['date'];

            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["Doc_File"]["name"]);
            $Doc_FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Handle file upload
            if (move_uploaded_file($_FILES["Doc_File"]["tmp_name"], $target_file)) {
                echo "SQL statement Bracket";

                // Prepare the SQL statement to update the existing record
                $stmt = $conn->prepare("UPDATE diadikasies SET Doc_Category = ?, Doc_Title = ?, Doc_Version = ?, Doc_Date = ?, Doc_File = ?, Doc_FileType = ? WHERE Doc_PaperID = ?");
                $stmt->bind_param("sssssss", $Doc_Category, $Doc_Title, $Doc_Version, $Doc_Date, $target_file, $Doc_FileType, $Doc_PaperID);

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
            echo "No file received or file upload error.";
        }
    } else {
        echo "Η ανάρτηση του αρχείου ακυρώθηκε.";
    }

    $conn->close();
}
?>