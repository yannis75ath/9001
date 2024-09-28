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

<Html>
<link rel="stylesheet" href="../style.css">
<body>
<h2><b>Καλώς ήρθατε, διαχειριστή!</b></h2>
<p>Εδώ μπορείτε να ανεβάσετε και να επεξεργαστείτε έγγραφα.</p>

<p><b>Μενου Διαχέιρησης Χρηστών</b></p>
<a href="view_registered_users.php">Προβολή Εγγεγραμένων Χρηστών της Εφαρμογής</a><p><p>
<a href="register_user_form.php">Εγγραφή Νέου Χρήστη</a><p><p>

<p><b>Μενου Διαδικασιών</b></p>
<a href="upload_diadikasia_form.php">Δημοσίευση Νέας Διαδικασίας</a><p><p>
<a href="..\display_diadikasies.php">Προβολή Διαδικασίών</a><p><p>
<a href="edit_diadikasies.php">Επεξεργασία Διαδικασιών</a><p><p>

<p><b>Έξοδος από τη Διαχείρηση της Εφαρμογής</b></p>
<a href="logout.php">Logout</a>




</body>
</html>