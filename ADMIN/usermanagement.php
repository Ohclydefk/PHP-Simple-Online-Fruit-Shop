<?php
require_once('database-connection/database-connection.php');

if (isset($_POST['updt_user'])) {
  $status = $_POST['status'];
  $user_id = $_POST['user_id'];
  $username = $_POST['username'];

  $update_user = $conn->query("UPDATE accounts SET status = '$status' WHERE uid = $user_id");
  $_SESSION['update_message'] = "$username's status has been updated to \"$status\"";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/user-homepage.css">
  <link rel="icon" type="image/png" href="images/logo-index.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fruiting In a Mood- Peachy Mood No More &#128541;</title>
</head>

<body>
  <div class="container-fluid">
    <?php
    @include 'navbar.php';
    ?>

    <div class="container">
      <h2 class="intro-title mb-2">User's Management</h2>

      <div class="row">
        <div class="col-md-12 mt-4">

          <?php if (isset($_SESSION['update_message'])) : ?>
            <h5 class="alert alert-success">
              <?= $_SESSION['update_message']; ?>
            </h5>
          <?php
            unset($_SESSION['update_message']);
          endif;
          ?>

          <div class="card">
            <div class="card-header">
              <h3 style="color:black;">List of Users</h3>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <th>ID</th>
                  <th>Username</th>
                  <th>Status</th>
                  <th class="text-center">Update</th>

                </thead>
                <tbody>
                  <?php
                  $query = "SELECT * FROM accounts WHERE role = 'customer'";
                  $statement = $conn->prepare($query);
                  $statement->execute();

                  $statement->setFetchMode(PDO::FETCH_OBJ); //PDO::FETCH_ASSOC
                  $result = $statement->fetchAll();
                  if ($result) {
                    foreach ($result as $row) {
                  ?>
                      <tr>
                        <td>
                          <?= $row->uid; ?>
                        </td>

                        <td>
                          <?= $row->username; ?>
                        </td>

                        <form method="POST">
                          <td>
                            <select class="form-control status" name="status">
                              <option value="Active" <?= ($row->status == 'Active') ? 'selected' : ''; ?>>Active</option>
                              <option value="Deactivated" <?= ($row->status == 'Deactivated') ? 'selected' : ''; ?>>Deactivated</option>
                              <option value="Blocked" <?= ($row->status == 'Blocked') ? 'selected' : ''; ?>>Blocked</option>
                            </select>
                          </td>

                          <td class="text-center">
                            <input type="hidden" name="user_id" value="<?= $row->uid ?>">
                            <input type="hidden" name="username" value="<?= $row->username ?>">
                            <input type="submit" class="btn btn-primary" name="updt_user" value="&#10000; Update Status">
                          </td>
                        </form>


                      </tr>
                    <?php
                    }
                  } else {
                    ?>
                    <tr>
                      <td colspan="3">No Record Found</td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>



</body>

</html>