<?php
// Include database connection
include 'admin/db.php';

// SQL query to select all documents
$sql = "SELECT Doc_ID, Doc_Category, Doc_PaperID, Doc_Title, Doc_Version, Doc_Date, Doc_File, Doc_FileType FROM diadikasies";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">	
    <title>Λίστα Εγγράφων</title>  
</head>
<body>
    <h2>Λίστα Εγγράφων</h2>
    <table>
        <tr>
            <th>Κατηγορία</th>
            <th>Έντυπο ή Τίτλος Διαδικασίας</th>
            <th>Τίτλος Εντύπου</th>
            <th>Έκδοση</th>
            <th>Ημερομηνία</th>
            <th>Τύπος Αρχείου</th>
        </tr>
        <?php
        // Check if there are results and display them
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Doc_Category"] . "</td>
                        <td>" . $row["Doc_PaperID"] . "</td>
                        <td>" . $row["Doc_Title"] . "</td>
                        <td>" . $row["Doc_Version"] . "</td>
                        <td>" . date('d/m/Y', strtotime($row["Doc_Date"])) . "</td>
                        <td><a href='9001/" . $row["Doc_File"] . "' target='_blank'>" . getIcon($row["Doc_FileType"]) . "</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Δεν υπάρχουν έγγραφα</td></tr>";
        }

        // Function to get the icon based on file type
        function getIcon($fileType) {
            $icons = array(
                "pdf" => "<img src='images/pdf-icon_35x38.png' alt='PDF' width='35' height='38'>", // You can replace this with a link to an actual icon
                "docx" => "<img src='images/word-icon_36x38.png' alt='DOCX' width='36' height='38'>",
                "xlsx" => "<img src='images/excel-icon_36x38.png' alt='XLSX' width='36' height='38'>"
            );
            return isset($icons[$fileType]) ? $icons[$fileType] : "📁"; // Default icon
        }

        // Close the database connection
        $conn->close();
        ?>
    </table>
</body>
</html>