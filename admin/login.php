<?php
include 'db.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php"); // Redirect after successful login
            exit();
        } else {
            echo "Λάθος κωδικός!";
        }
    } else {
        echo "Ο χρήστης δεν βρέθηκε!";
        echo "Ελέγξτε τα στοιχεία σας και προσπαθήστε ξανά.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Σύνδεση</title>
	<link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Φόρμα Σύνδεσης</h2>
    <form action="login.php" method="POST">
        <label for="username">Όνομα Χρήστη:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Κωδικός:</label><br>
        <input type="password" id="password" name="password" required><br><br>
		<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"> <!-- CSRF protection Token -->
        <button type="submit">Σύνδεση</button>
    </form>
</body>
</html>