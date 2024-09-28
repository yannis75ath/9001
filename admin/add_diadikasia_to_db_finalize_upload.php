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

// Check if user confirmed the upload
if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    // Get form data from session
    $form_data = $_SESSION['form_data'];
    $original_filename = $_SESSION['original_filename'];

    // Final file destination
    $target_dir = "../uploads/";
    $final_target_file = $target_dir . basename($original_filename); // Use original filename

    // Move the temporary file to the final directory with original filename
    if (rename("../uploads/temp/" . $original_filename, $final_target_file)) {
        // Prepare the SQL statement to update the existing record in the database
        $stmt = $conn->prepare("UPDATE diadikasies SET Doc_Category = ?, Doc_PaperID = ?, Doc_Title = ?, Doc_Version = ?, Doc_Date = ?, Doc_File = ?, Doc_FileType = ? WHERE Doc_ID = ?");
        $file_extension = pathinfo($final_target_file, PATHINFO_EXTENSION);

        $stmt->bind_param("sssssssi", 
            $form_data['Doc_Category'], 
            $form_data['Doc_PaperID'], 
            $form_data['Doc_Title'], 
            $form_data['Doc_Version'], 
            $form_data['Doc_Date'], 
            $final_target_file, 
            $file_extension, 
            $form_data['Doc_ID']
        );

        if ($stmt->execute()) {
            echo "Το έγγραφο ενημερώθηκε επιτυχώς!";
        } else {
            echo "Σφάλμα κατά την ενημέρωση του εγγράφου: " . $stmt->error;
        }

        $stmt->close();

        // Clear session data
        unset($_SESSION['original_filename']);
        unset($_SESSION['form_data']);

        // Redirect to the edit list page
        header("Location: edit_diadikasies.php");
        exit();
    } else {
        echo "Υπήρξε πρόβλημα κατά τη μετακίνηση του αρχείου.";
    }
} else if (isset($_POST['confirm']) && $_POST['confirm'] === 'no') {
    // User chose not to upload the file, delete the record from the database
    $form_data = $_SESSION['form_data'];
    $Doc_ID = $form_data['Doc_ID'];

    // Prepare and execute delete statement
    $stmt = $conn->prepare("DELETE FROM diadikasies WHERE Doc_ID = ?");
    $stmt->bind_param("i", $Doc_ID);

    if ($stmt->execute()) {
        echo "Η καταχώριση και το αρχείο διαγράφηκαν επιτυχώς.";
    } else {
        echo "Σφάλμα κατά τη διαγραφή της καταχώρισης: " . $stmt->error;
    }

    $stmt->close();

    // Delete the temporary file if it exists
    if (isset($_SESSION['original_filename'])) {
        $temp_file = "../uploads/temp/" . $_SESSION['original_filename'];
        if (file_exists($temp_file)) {
            unlink($temp_file);
        }
    }

    // Clear session data
    unset($_SESSION['original_filename']);
    unset($_SESSION['form_data']);

    // Redirect back to the form or another appropriate page
    header("Location: upload_diadikasia_form.php?cancelled=true");
    exit();
}

// Close the database connection
$conn->close();
?>