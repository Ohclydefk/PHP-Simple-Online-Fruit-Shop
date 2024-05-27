<?php
require_once('database-connection/database-connection.php');
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/product-list.css">

  <title>List Of Users</title>
</head>

<body>

  <div class="container">
    <div class="row">
      <div class="col-md-12 mt-4">
        <div class="card border-0 shadow">
          <div class="card-body">
            <h3 class="card-title mb-4">List of Customers</h3>
            <table class="table table-light table-bordered rounded-3 table-striped">
              <thead class="bg-success">
                <tr>
                  <th>ID</th>
                  <th>Username</th>
                  <th>Status</th>
                  <th>Full Name</th>
                  <th>Contact</th>
                  <th>Email</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = "SELECT * FROM accounts WHERE role = 'customer'";
                $statement = $conn->prepare($query);
                $statement->execute();

                $statement->setFetchMode(PDO::FETCH_OBJ);
                $result = $statement->fetchAll();
                if ($result) {
                  foreach ($result as $row) {
                ?>
                    <tr>
                      <td class="fw-bold text-primary"><?= $row->uid; ?></td>
                      <td><?= $row->username; ?></td>
                      <td><?= $row->status; ?></td>
                      <td><?= $row->fname . ' ' . $row->lname ?></td>
                      <td><?= $row->phonenumber; ?></td>
                      <td><?= $row->email; ?></td>
                    </tr>
                  <?php
                  }
                } else {
                  ?>
                  <tr>
                    <td colspan="6">No Record Found</td>
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
