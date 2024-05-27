<?php 
$server = "localhost";
$username = "root";
$password = null;
$database = "it26";
$port = 3306;

try {
  $conn = new PDO("mysql:host=$server;dbname=$database;port=$port", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  # echo "Connected Successfully"; 
} catch(PDOException $e) {
  echo "Connection Failed: " . $e->getMessage();
  header('Location: index.php?error=conenction-to-database-failed');
}
?>