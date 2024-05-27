<?php
require_once('database-connection/database-connection.php');
include 'Validation_script/signup-validation.php';

if (!empty($errorMessage)) {
  echo '<script>alert(\'' . $errorMessage .'\'); window.location=\'signup.php\';</script>';  
  exit();

} else {

  try {
    # DATA RETRIEVAL RANI
    $get_fname = $_POST['fname'];
    $get_lname = $_POST['lname'];
    $get_telnum = $_POST['telnum'];
    $get_address = $_POST['address'];
    $get_city = $_POST['city'];
    $get_zip = $_POST['zip'];
    $get_email = $_POST['email'];
    $get_username = $_POST['username'];
    $get_password = $_POST['password'];

    # For sanitizing ni sa inputs (for security purpose)- kailangan jud ni pero e explore pa nako ni nga function
    $get_fname = filter_var($get_fname, FILTER_SANITIZE_STRING);
    $get_lname = filter_var($get_lname, FILTER_SANITIZE_STRING);
    $get_telnum = filter_var($get_telnum, FILTER_SANITIZE_STRING);
    $get_address = filter_var($get_address, FILTER_SANITIZE_STRING);
    $get_city = filter_var($get_city, FILTER_SANITIZE_STRING);
    $get_zip = filter_var($get_zip, FILTER_SANITIZE_STRING);
    $get_email = filter_var($get_email, FILTER_SANITIZE_EMAIL);
    $get_username = filter_var($get_username, FILTER_SANITIZE_STRING);
    $get_password = filter_var($get_password, FILTER_SANITIZE_STRING);

    $stmt = $conn->prepare("INSERT INTO accounts (fname, lname, phonenumber, address, city, zip, email, username, password) 
  VALUES (:fname, :lname, :telnum, :address, :city, :zip, :email, :username, :password)");

    # Diri na part kay need nato e encrypt ang password for security purpose japun.
    $passwordEncrypt = password_hash($get_password, PASSWORD_DEFAULT);

    $stmt->bindParam(':fname', $get_fname, PDO::PARAM_STR);
    $stmt->bindParam(':lname', $get_lname, PDO::PARAM_STR);
    $stmt->bindParam(':telnum', $get_telnum, PDO::PARAM_INT);
    $stmt->bindParam(':address', $get_address, PDO::PARAM_STR);
    $stmt->bindParam(':city', $get_city, PDO::PARAM_STR);
    $stmt->bindParam(':zip', $get_zip, PDO::PARAM_STR);
    $stmt->bindParam(':email', $get_email, PDO::PARAM_STR);
    $stmt->bindParam(':username', $get_username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $passwordEncrypt, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt) {
      echo '<script>alert(\'Successfuly Signed Up! Welcome to FIM, Enjoy our best quality products and discounts.\'); 
        window.location=\'login.php\';</script>';  
      exit();

    } else {
      echo "<h4 class='text-danger'>Unknown Error 0069</h4>";
      exit();
    }


  } catch (PDOException $e) {
    echo "<h4 class='text-danger'>Unknown Error 0069</h4>";
    exit();
  }


}




?>