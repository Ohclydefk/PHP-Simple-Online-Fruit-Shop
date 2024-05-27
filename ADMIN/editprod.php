<?php
session_start();
require_once('database-connection/database-connection.php');

if(isset($_POST['delete_prod_btn'])) {
    $productID = $_POST['productID'];

    $stmt = $conn->prepare('DELETE FROM fruits WHERE productID = ?');
    $stmt->execute([$productID]);

    if($stmt->rowCount() > 0) {
        $_SESSION['pmessage'] = "Deleted Successfully";
    } else {
        $_SESSION['pmessage'] = "Not Deleted";
    }

    header('Location: productmanagement.php');
    exit();
}
?>
