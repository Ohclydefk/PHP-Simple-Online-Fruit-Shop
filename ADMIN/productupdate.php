<?php
session_start();
require_once('database-connection/database-connection.php');


$max_file_size = 2 * 1024 * 1024; 



if (isset($_POST['update_prod_btn'])) {
    $product_id = $_POST['productID'];
    $product_name = $_POST['productname'];
    $unit_price = $_POST['unitprice'];
    $stocks = $_POST['stocks'];
    $unit_of_measurement = $_POST['unitofmeasurement'];

    if (isset($_FILES['productimage']) && $_FILES['productimage']['error'] === 0) {
        if ($_FILES['productimage']['size'] <= $max_file_size) {
            $product_image_content = file_get_contents($_FILES['productimage']['tmp_name']);
        } else {
            echo 'Error: The file size exceeds the limit of 2 MB.';
            exit();
        }
    } else {
        $stmt = $conn->prepare('SELECT productimage FROM fruits WHERE productID = ?');
        $stmt->execute([$product_id]);
        $product_image_content = $stmt->fetchColumn();
    }
    

    $stmt = $conn->prepare('UPDATE fruits SET productname = ?, unitprice = ?, unitofmeasurement = ?, productimage = ?, stocks = ? WHERE productID = ?');
    $stmt->bindParam(1, $product_name);
    $stmt->bindParam(2, $unit_price);
    $stmt->bindParam(3, $unit_of_measurement);
    $stmt->bindParam(4, $product_image_content, PDO::PARAM_LOB);
    $stmt->bindParam(5, $stocks);
    $stmt->bindParam(6, $product_id);
    $stmt->execute();

    header('Location: productmanagement.php');
    exit();
}

if (!isset($_GET['productID'])) {
    header('Location: productmanagement.php');
    exit();
}

$stmt = $pdo->prepare('SELECT productID, productname, unitprice, unitofmeasurement, productimage FROM fruits WHERE productID = ?');
$stmt->execute([$_GET['productID']]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: productmanagement.php');
    exit(0);
}



if(isset($_POST['delete_prod_btn']))
{
    $productID = $_POST['productID'];

    try {

        $query = "DELETE FROM fruits WHERE productID=:productID";
        $statement = $conn->prepare($query);
        $data = [
            ':productID' => $productID,
        ];
        $query_execute = $statement->execute($data);

        if($query_execute)
        {
            $_SESSION['pmessage'] = "Deleted Successfully";
            header('Location: productmanagement.php');
            exit(0);
        }
        else
        {
            $_SESSION['pmessage'] = "Not Deleted";
            header('Location: productmanagement.php');
            exit(0);
        }

    } catch(PDOException $e){
        echo $e->getMessage();
    }
}

if(isset($_POST['add_prod_btn']))
{
    
    $productname = $_POST['productname'];
    $unitprice = $_POST['unitprice'];
    $unitofmeasurement = $_POST['unitofmeasurement'];
   
    

        $query = "INSERT INTO fruits (productname, unitprice, unitofmeasurement) VALUES (:productname, :unitprice, :unitofmeasurement)";
        $statement = $conn->prepare($query);

        $data = [
            ':productname' => $productname,
            ':unitprice' => $unitprice,
            ':unitofmeasurement' => $unitofmeasurement,
            
      
            
        ];
        $query_execute = $statement->execute($data);
      
        if($query_execute)
        {
            $_SESSION['pmessage'] = "Product Added";
            header('Location: productmanagement.php');
            exit(0);
        }
        else
        {
            $_SESSION['pmessage'] = "Not Added";
            header('Location: productmanagement.php');
            exit(0);
    }
}

?>