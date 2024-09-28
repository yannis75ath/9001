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
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Εγγραφή Χρήστη</title>
	<link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Φόρμα Εγγραφής Χρήστη</h2>
    <form action="register_user_to_db.php" method="POST">
        <label for="username">Όνομα Χρήστη:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Κωδικός:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="role">Ρόλος:</label><br>
        <select id="role" name="role" required>
            <option value="user">Χρήστης</option>
            <option value="admin">Διαχειριστής</option>
        </select><br><br>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"> <!-- CSRF protection Token -->
        <button type="submit">Εγγραφή</button>
    </form>
</body>
</html>