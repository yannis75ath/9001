<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_httponly' => true,
        'use_strict_mode' => true
    ]);
}

//ΠΡΟΣΩΡΙΝΑ ΘΑ ΦΑΙΝΕΤΑΙ ΤΟ ΤΟΚΕΝ
global $_SESSION; // Add this line
print_r($_SESSION);


if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$servername = "localhost";
$username = "Admin"; // Default username in XAMPP
$password = "quality9@@1"; // Default password for XAMPP is empty
$dbname = "9001"; // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
//  die("Connection failed: " . $conn->connect_error); // this display the error to users we dont want that!
	error_log("Connection failed: " . $conn->connect_error . "\n", 3, "sql_error_log.log");  //write the error to a log file
    die("Connection failed");
}

echo "Connected successfully";
?>
