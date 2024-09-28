<?php
include 'db.php'; // Include your database connection file

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted + SQL injection Secutiry
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

	// Check if the username exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "Το όνομα χρήστη υπάρχει ήδη!";
    } else {
        // Insert the new user
	$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
		if ($stmt->execute()) {
			echo "Ο χρήστης δημιουργήθηκε επιτυχώς!";
		} else {
			echo "Σφάλμα: " . $stmt->error;
		}

    $stmt->close();
    $conn->close();
	}

}
?>

