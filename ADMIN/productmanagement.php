<?php
session_start();
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
  <link rel="icon" type="image/png" href="images/logo-index.png">
  <title>Product Management</title>
</head>

<body>
  <?php include('navbar.php') ?>
  <div class="container mb-5">
    <div class="d-flex align-items-center">
      <img src="images/logo-index.png" alt="logo" width="150">
      <h2 class="intro-title thizTitle">Product Management</h2>
    </div>

    <div class="card mt-3">
      <div class="card-header mt-3 px-4">
        <h3 class="text-dark">Products on Stocks <a href="addproduct.php" class="btn btn-primary float-end">
          <strong>&#43; </strong> Add Product</a></h3>
      </div>

      <?php if (isset($_SESSION['pmessage'])) : ?>
        <h5 class="alert alert-success">
          <?= $_SESSION['pmessage']; ?>
        </h5>
      <?php
        unset($_SESSION['pmessage']);
      endif;
      ?>

      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead class="text-center">
            <th>Product ID</th>
            <th>Image</th>
            <th>Stocks</th>
            <th class="text-start">Product Name</th>
            <th>Price</th>
            <th>Measurement</th>
            <th>Edit</th>
            <th>Remove</th>
          </thead>
          <tbody>
            <?php
            $query = "SELECT * FROM fruits";
            $statement = $conn->prepare($query);
            $statement->execute();

            $statement->setFetchMode(PDO::FETCH_OBJ); //PDO::FETCH_ASSOC
            $result = $statement->fetchAll();
            if ($result) {
              foreach ($result as $row) {
            ?>
                <tr class="text-center">
                  <td class="align-middle">
                    <?= $row->productID; ?>
                  </td>
                  
                  <td class="align-middle">
                    <?php echo '<img class="prod-img" src="data:productimage;base64,' . base64_encode($row->productimage) . '" alt="Image" style="width: 90px; height: 80px;">' ?>
                  </td>

                  <td class="align-middle">
                    <?= $row->stocks; ?>
                  </td>

                  <td class="align-middle text-start">
                    <?= $row->productname; ?>
                  </td>

                  <td class="align-middle">
                    <?php echo '&#8369;' . $row->unitprice; ?>
                  </td>

                  <td class="align-middle">
                    <?= $row->unitofmeasurement; ?>
                  </td>

                  <td class="align-middle text-center">
                    <a href="updateproduct.php?productID=<?= $row->productID; ?>" class="btn btn-success">&#10000; Edit Product</a>
                  </td>

                  <td class="align-middle">
                    <form action="editprod.php" method="POST">
                      <input type="hidden" name="productID" value="<?= $row->productID ?>">
                      <button type="submit" name="delete_prod_btn" class="btn btn-danger w-100 text-center" onclick="return confirm('Are you sure you want to delete this product?')">🗑 Delete</button>
                    </form>
                  </td>


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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>