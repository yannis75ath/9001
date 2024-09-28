<?php
// Include database connection file
include 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// SQL query to select all users
$sql = "SELECT user_id, username, role, created_at FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Λίστα Χρηστών</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Λίστα Χρηστών</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Όνομα</th>
            <th>Ρόλος</th>
            <th>Ημερομηνία Εγγραφής</th>
        </tr>
        <?php
        // Check if there are results and display them
        if ($result->num_rows > 0) {
            // Output data for each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["user_id"]. "</td>
                        <td>" . $row["username"]. "</td>
                        <td>" . $row["role"]. "</td>
                        <td>" . $row["created_at"]. "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Δεν υπάρχουν εγγραφές</td></tr>";
        }
        // Close the database connection
        $conn->close();
        ?>
    </table>
</body>
</html>