<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_httponly' => true,
        'use_strict_mode' => true
    ]);
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Check if temp file and form data exist in session
if (!isset($_SESSION['original_filename']) || !isset($_SESSION['form_data'])) {
    echo "Δεν υπάρχουν δεδομένα για επιβεβαίωση.";
    exit();
}

// Get form data and temp file from session
$form_data = $_SESSION['form_data'];
$original_filename = $_SESSION['original_filename'];

// Check if the file already exists in the final upload directory
$final_target_file = "../uploads/" . basename($original_filename);
$file_exists = file_exists($final_target_file);
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Επιβεβαίωση Αποστολής Αρχείου</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h2>Επιβεβαίωση Αποστολής Αρχείου</h2>

<?php if ($file_exists): ?>
    <p>Είστε σίγουροι ότι θέλετε να αντικαταστήσετε το υπάρχον αρχείο με το νέο αρχείο;</p>
    <p><strong>Τίτλος Εντύπου:</strong> <?php echo htmlspecialchars($form_data['Doc_Title']); ?></p>
    <p><strong>Κατηγορία Εγγράφου:</strong> <?php echo htmlspecialchars($form_data['Doc_Category']); ?></p>
    <p><strong>Υπάρχον αρχείο:</strong> <?php echo htmlspecialchars($original_filename); ?></p>
<?php else: ?>
    <p>Δεν υπάρχει υπάρχον αρχείο με το όνομα: <?php echo htmlspecialchars($original_filename); ?>. Προσθήκη νέου αρχείου.</p>
<?php endif; ?>

<form action="add_diadikasia_to_db_finalize_upload.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="hidden" name="Doc_ID" value="<?php echo htmlspecialchars($form_data['Doc_ID']); ?>"> <!-- Pass the record ID -->
    <button type="submit" name="confirm" value="yes">Ναι</button>
    <button type="submit" name="confirm" value="no">Όχι</button>
</form>
</body>
</html>