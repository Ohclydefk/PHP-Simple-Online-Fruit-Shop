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
  <link rel="stylesheet" href="custom-css/updt-prod-styles.css">
  <title>Update Product</title>
</head>

<body>
  <div class="updt-cont container mt-4">
    <?php
    if (isset($_GET['productID'])) {
      $productID = $_GET['productID'];

      $query = "SELECT * FROM fruits WHERE productID=:productID LIMIT 1";
      $statement = $conn->prepare($query);
      $data = [':productID' => $productID];
      $statement->execute($data);

      $result = $statement->fetch(PDO::FETCH_OBJ); //PDO::FETCH_ASSOC
    }

    ?>
    <form action="productupdate.php" method="POST" enctype="multipart/form-data">
      <div class="float-end">
        <button type="submit" name="update_prod_btn" class="btn btn-success" onclick="return confirm('Save Changes?')">🗸 Update</button>
        <a href="productmanagement.php" class="btn btn-danger px-4">&#8592;</a>
      </div>
      <h3 class="mb-5 text-dark">Update your products here.</h3>

      <div class="row row-cols-2 mb-3 align-items-center">
        <div class="col-2">
          <label for="productname" class="text-dark">Product Name</label>
        </div>

        <div class="col-7">
          <input type="text" name="productname" class="form-control" value="<?php echo $result->productname ?>" required>
          <input type="hidden" name="productID" value="<?php echo $result->productID ?>">
        </div>
      </div>

      <div class="row row-cols-2 mb-3 align-items-center">
        <div class="col-2">
          <label for="productname" class="text-dark">Product Supply</label>
        </div>

        <div class="col-7">
          <input type="text" class="form-control" value="<?php echo $result->stocks ?>" required>
          <input type="hidden" name="stocks" value="<?php echo $result->stocks ?>">
        </div>
      </div>

      <div class="row row-cols-2 mb-3 align-items-center">
        <div class="col-2">
          <label for="productname" class="text-dark">Product Price</label>
        </div>

        <div class="col-7">
          <input type="text" class="form-control" value="<?php echo $result->unitprice ?>" required>
          <input type="hidden" name="unitprice" value="<?php echo $result->unitprice ?>">
        </div>
      </div>

      <div class="row row-cols-2 mb-3 align-items-center">
        <div class="col-2">
          <label for="productname" class="text-dark">Measurement</label>
        </div>

        <div class="col-7">
          <select class="form-control status" name="unitofmeasurement">
            <option value="Pcs" <?= ($result->unitofmeasurement == 'Pcs') ? 'selected' : ''; ?>>🦎 Pcs</option>
            <option value="Kilograms" <?= ($result->unitofmeasurement == 'Kilograms') ? 'selected' : ''; ?>>🦎 Kilograms</option>
            <option value="Grams" <?= ($result->unitofmeasurement == 'Grams') ? 'selected' : ''; ?>>🦎 Grams</option>
          </select>
        </div>
      </div>

      <div class="row row-cols-3 mb-3 align-items-center">
        <div class="col-2">
          <label for="productname" class="text-dark">Product Image</label>
        </div>

        <div class="col-5">
          <input type="file" name="productimage" class="form-control">
        </div>

        <div class="col">
          <?php if ($result->productimage) : ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($result->productimage) ?>" alt="<?php echo $result->productname ?>" width="130" height="130" class="prod-img">
          <?php endif; ?>
        </div>
      </div>
    </form>
  </div>
</body>

</html>