<?php
session_start();
require_once('database-connection/database-connection.php');

if (isset($_GET['uid'])) {
  $student_id = $_GET['uid'];

  $query = "SELECT * FROM customer_table WHERE uid=:uid LIMIT 1";
  $statement = $conn->prepare($query);
  $data = [':uid' => $student_id];
  $statement->execute($data);

  $result = $statement->fetch(PDO::FETCH_OBJ); //PDO::FETCH_ASSOC
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit & Update data into database using PHP PDO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <div class="row mt-4">
      <div class="col-md-8 mx-auto">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="m-0">Update User Status</h3>
            <a href="usermanagement.php" class="btn btn-danger px-4">Back</a>
          </div>
          <div class="card-body">
            <form action="userupdate.php" method="POST">
              <div class="mb-3">
                <label for="uid" class="form-label">User ID</label>
                <input type="text" id="uid" name="uid" value="<?= $result->uid; ?>" class="form-control bg-primary" readonly />
              </div>
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" value="<?= $result->username; ?>" class="form-control bg-primary" readonly />
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select">
                  <option value="Active" <?= $result->status === 'Active' ? 'selected' : ''; ?>>Active
                  </option>
                  <option value="Blocked" <?= $result->status === 'Blocked' ? 'selected' : ''; ?>>Blocked
                  </option>
                  <option value="Deactivated" <?= $result->status === 'Deactivated' ? 'selected' : ''; ?>>Deactivated</option>
                </select>
              </div>
              <div class="mb-3">
                <button type="submit" name="update_user_btn" class="btn btn-primary">Update
                  Status</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>