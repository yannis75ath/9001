<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_httponly' => true,
        'use_strict_mode' => true
    ]);
}

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the 'cancelled' parameter is present in the URL
if (isset($_GET['cancelled']) && $_GET['cancelled'] === 'true') {
    // Handle the cancellation action
    // You can display a message or take specific actions here
    $cancel_message = "Η διαδικασία ακυρώθηκε. Τα δεδομένα καταχώρισης και το προσωρινό αρχείο διαγράφηκαν επιτυχώς.";
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Προσθήκη Διαδικασίας</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Φόρμα Προσθήκης Διαδικασίας</h2>

    <?php
    // Display cancellation message if the operation was cancelled
    if (isset($cancel_message)) {
        echo "<p style='color: red;'>$cancel_message</p>";
    }
    ?>

    <form action="add_diadikasia_to_db.php" method="POST" enctype="multipart/form-data">
        <label for="Doc_Category">Κατηγορία Εγγράφου:</label><br>
        <input type="text" id="Doc_Category" name="Doc_Category" maxlength="5" required><br><br>
        
        <label for="Doc_PaperID">Έντυπο ή Τίτλος Διαδικασίας:</label><br>
        <input type="text" id="Doc_PaperID" name="Doc_PaperID" maxlength="8" required><br><br>
        
        <label for="Doc_Title">Τίτλος Εντύπου:</label><br>
        <input type="text" id="Doc_Title" name="Doc_Title" maxlength="200" required><br><br>
        
        <label for="Doc_Version">Έκδοση:</label><br>
        <select id="Doc_Version" name="Doc_Version" required>
            <?php 
            for ($i = 1; $i <= 99; $i++) {
                echo "<option value='$i'>$i</option>";
            }
            ?>
        </select><br><br>
        
        <label for="Doc_Date">Ημερομηνία:</label><br>
        <input type="date" id="Doc_Date" name="Doc_Date" required><br><br>
        
        <label for="Doc_File">Τύπος Αρχείου:</label><br>
        <input type="file" id="Doc_File" name="Doc_File" accept=".pdf,.docx,.xlsx" required><br><br>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"> <!-- CSRF protection Token -->
        <button type="submit">Αποστολή</button>
    </form>
</body>
</html>