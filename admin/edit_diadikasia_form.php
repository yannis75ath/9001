<?php
include 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Get the record ID from the URL
$doc_id = $_GET['id'] ?? null;

// Fetch the existing record data
$sql = "SELECT * FROM diadikasies WHERE Doc_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doc_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $record = $result->fetch_assoc();
} else {
    echo "Δεν βρέθηκε το έγγραφο.";
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Επεξεργασία Διαδικασίας</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<h2>Επεξεργασία Διαδικασίας</h2>
<form action="edit_diadikasia_to_db.php" method="POST" enctype="multipart/form-data">
    <label for="Doc_Category">Κατηγορία Εγγράφου:</label><br>
    <input type="text" id="Doc_Category" name="Doc_Category" value="<?php echo htmlspecialchars($record['Doc_Category']); ?>" required><br><br>
    
    <label for="Doc_PaperID">Έντυπο ή Τίτλος Διαδικασίας:</label><br>
    <input type="text" id="Doc_PaperID" name="Doc_PaperID" value="<?php echo htmlspecialchars($record['Doc_PaperID']); ?>" required><br><br>
    
    <label for="Doc_Title">Τίτλος Εντύπου:</label><br>
    <input type="text" id="Doc_Title" name="Doc_Title" value="<?php echo htmlspecialchars($record['Doc_Title']); ?>" required><br><br>
    
    <label for="Doc_Version">Έκδοση:</label><br>
    
	<select id="Doc_Version" name="Doc_Version" required>
            <?php 
            for ($i = 1; $i <= 99; $i++) {
                $selected = $record['Doc_Version'] == $i ? 'selected' : '';
                echo "<option value='$i' $selected>$i</option>";
            }
            ?></select><br><br>
 <label for="Doc_Date">Ημερομηνία:</label><br>
    <input type="date" id="Doc_Date" name="Doc_Date" value="<?php echo htmlspecialchars($record['Doc_Date']); ?>" required><br><br>
    
    <label for="Doc_File">Νέο αρχείο (προαιρετικό):</label><br>
    <input type="file" id="Doc_File" name="Doc_File" accept=".pdf,.docx,.xlsx"><br><br>
    
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"> <!-- CSRF protection Token -->
    <input type="hidden" name="Doc_ID" value="<?php echo $doc_id; ?>"> <!-- Pass the record ID -->
    <button type="submit">Αποθήκευση Αλλαγών</button>
</form>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>