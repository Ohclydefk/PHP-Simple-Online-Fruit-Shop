<?php
# Error Handlers ni nga page, ayaw nig taruga
require_once('database-connection/database-connection.php');
$errorMessage = '';

if (isset($_POST['sign-up']) || isset($_POST['confirm-purchase'])) {
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $telnum = $_POST['telnum'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $zip = $_POST['zip'];
  $email = $_POST['email'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm-password'];

  if (checkIfEmailExist($conn, $email) !== false) {
    $errorMessage .= '⚠ Email already exists, please use a different.\n\n';
  }

  if (checkIfUsernameExist($conn, $username) !== false) {
    $errorMessage .= '⚠ Username already exists, please use a different one.\n\n';
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errorMessage .= '⚠ Invalid email, please use a valid one.\n\n';
  }

  if (strlen($password) < 8) {
    $errorMessage .= '⚠ Your password is too short. It must be 8 or more characters long.\n\n';
  }

  if (!preg_match('/[A-Z]/', $password)) {
    $errorMessage .= '⚠ Password must contain at least one uppercase character.\n\n';
  }

  if (!preg_match('/[0-9]/', $password)) {
    $errorMessage .= '⚠ Password must contain at least one number.\n\n';
  }

  if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+]/', $password)) {
    $errorMessage .= '⚠ Password must contain at least one special character.\n\n';
  }

  if ($password != $confirm_password) {
    $errorMessage .= '⚠ Passwords do not match.\n\n';
  }

  if (checkEmptyFields($fname, $lname, $telnum, $email, $username, $password, $confirm_password) !== false) {
    $errorMessage .= '⚠ All fields are required.\n\n';
  }
}

function checkEmptyFields($fname, $lname, $telnum, $email, $username, $password, $confirm_password)
{
  $isFieldEmpty = false;
  if (empty($fname) || empty($lname) || empty($telnum) || empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
    $isFieldEmpty = true;
  } else {
    $isFieldEmpty = false;
  }

  return $isFieldEmpty;
}

function checkIfEmailExist($conn, $email) {
  $doExist = false;
  $existingEmailQuery = "SELECT * FROM accounts WHERE email = :email";
  $existingEmail = $conn->prepare($existingEmailQuery);
  $existingEmail->bindParam(':email', $email);
  $existingEmail->execute();
  $existingEmail = $existingEmail->fetch(PDO::FETCH_ASSOC);

  if ($existingEmail) {
    $doExist = true;
  } else {
    $doExist = false;
  }

  return $doExist;
}

function checkIfUsernameExist($conn, $username) {
  $doExist = false;
  $existingUserQuery = "SELECT * FROM accounts WHERE username = :username";
  $existingUser = $conn->prepare($existingUserQuery);
  $existingUser->bindParam(':username', $username);
  $existingUser->execute();
  $existingEmail = $existingUser->fetch(PDO::FETCH_ASSOC);

  if ($existingEmail) {
    $doExist = true;
  } else {
    $doExist = false;
  }

  return $doExist;
}

?>