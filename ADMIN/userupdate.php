<?php
session_start();
require_once('database-connection/database-connection.php');

if(isset($_POST['update_user_btn']))
{
    $uid = $_POST['uid'];
    $username = $_POST['username'];
    $status = $_POST['status'];

    try {

        $query = "UPDATE customer_table SET username=:username, status=:status WHERE uid=:uid LIMIT 1";
        $statement = $conn->prepare($query);

        $data = [
            ':username' => $username,
            ':status' => $status,
            ':uid' => $uid,
            
        ];
        $query_execute = $statement->execute($data);

        if($query_execute)
        {
            $_SESSION['message'] = "Updated Successfully";
            header('Location: usermanagement.php');
            exit(0);
        }
        else
        {
            $_SESSION['message'] = "Not Updated";
            header('Location: usermanagement.php');
            exit(0);
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>