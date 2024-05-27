<?php
require_once('database-connection/database-connection.php');
$stmt = $conn->prepare("SELECT * FROM fruits");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $selected_option = $_POST['filter'];

  # Execute if selected option is "all"
  if ($selected_option == 'all') {
    $stmt = $conn->prepare("SELECT * FROM fruits ORDER BY productname");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  # Execute if selected option is "popular"
  if ($selected_option == 'popular') {
    $stmt = $conn->prepare("SELECT * FROM fruits WHERE TOP != 0 ORDER BY TOP");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  # Execute if selected option is "lowest-price"
  elseif ($selected_option == 'lowest-price') {
    $stmt = $conn->prepare("SELECT * FROM fruits ORDER BY unitprice ASC");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  # Execute if selected option is "luxury"
  elseif ($selected_option == 'luxury-quality') {
    $stmt = $conn->prepare("SELECT * FROM fruits WHERE isBestSeller = 'true' ORDER BY unitprice DESC");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } else {
    header('Location: fruits.php?action=NULL');
  }
}
?>

<?php
$filterMsg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $selected_option = $_POST['filter'];

  if ($selected_option == "popular") {
    $filterMsg = "Most Popular Fruits";
  } elseif ($selected_option == "lowest-price") {
    $filterMsg = "Most Affordable Fruits";
  } elseif ($selected_option == "luxury-quality") {
    $filterMsg = "Best Seller Fruits";
  } elseif ($selected_option == "all") {
    $filterMsg = NULL;
  }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/product-list.css">
  <link rel="stylesheet" href="custom-css/simple-animation/fruits-animation.css">
  <link rel="icon" type="image/png" href="images/kiwi-big.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fruiting In a Mood- Ultimate Basket Fruit List</title>
</head>

<body>
  <?php include('navbar.php') ?>

  <div class="container ps-5 pe-5 d-flex flex-column justify-content-center">
    <h4 class="thizTitle mb-2">The Ultimate Fruit Basket: Our Top Picks</h4>
    <hr>
    <div class="mb-5 row">
      <div class="col-12">
        <form method="POST">
          <div class="mb-5 d-flex flex-row align-items-center w-50">
            <select name="filter" class="form-select w-lg-25 w-50">
              <option value="all" selected>All</option>
              <option value="popular">Popular</option>
              <option value="lowest-price">Lowest Price</option>
              <option value="luxury-quality">Best Seller</option>
            </select>
            <button type="submit" class="btn btn-primary ms-3">Filter</button>
          </div>
        </form>
      </div>

      <div class="col-12">
        <?php echo "<h5 class='text-warning'>" . $filterMsg . "</h5>" ?>
      </div>

    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
      <?php foreach ($results as $row) { ?>
        <div class="col mb-4">
          <div class="customized-card card h-100 card">
            <?php if ($row['isBestSeller'] == "true"):
              echo '
                <div class="best-seller-container">
                  <img src="images/best-seller.png" width="100%" heigth="100%">
                  <p>Best Seller</p>
                </div>
              ';
            endif; ?>
            <img class="card-img-top customized-img" loading="lazy"
              src='data:image/jpeg;base64,<?php echo base64_encode($row['productimage']) ?>'
              alt="<?php echo $row['productname'] ?>">
            <div class="card-body customized-card-body">
              <h5 class="customized-title card-title mb-3">
                <?php echo $row['productname'] ?>
              </h5>

              <p class="card-text text-dark mb-2">
                  Unit Price:
                  <span class="text-success fw-bold">
                    <?php echo '&#8369;' . $row['unitprice'] ?>
                  </span>
                  per
                  <?php echo $row['unitofmeasurement'] ?>
                </p>

                <p class="card-text text-dark mb-4">
                  Available:
                  <span class="fw-bold <?php
                    if ($row['stocks'] >= 100) {
                      echo 'text-success';
                    } elseif ($row['stocks'] >= 30) {
                      echo 'text-warning';
                    } else {
                      echo 'text-danger';
                    }
                  ?>"> <?php echo $row['stocks']?></span>
                </p>
              <form action="basket.php" method="POST">
                <div class="d-flex flex-row align-items-center">
                  <label for="quantity" class="form-label me-3 text-dark d-lg-inline-block d-none">Quantity:</label>
                  <input type="number" class="form-control me-4 w-50 d-inline-block qty-input" name="quantity" value="1"
                    min="1" max="15">
                    
                  <button type="submit" class="btn <?php echo ($row['stocks'] <= 0) ? 'btn-danger disabled' : 'btn-primary'; ?> d-inline-block" name="add-to-basket" 
                    <?php if ($row['stocks'] <= 0): ?> disabled <?php endif; ?>>
                    <img src="images/shopping-cart.png" alt="shopping-cart" width="26px" >
                  </button>

                </div>
                <input type="hidden" name="get_id" value="<?php echo $row['productID'] ?>">
                <input type="hidden" name="get_img" value="<?php echo base64_encode($row['productimage']) ?>">
                <input type="hidden" name="get_productname" value="<?php echo $row['productname'] ?>">
                <input type="hidden" name="get_price" value="<?php echo $row['unitprice'] ?>">
                <input type="hidden" name="get_unit" value="<?php echo $row['unitofmeasurement'] ?>">
              </form>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
  <?php include('footer.php') ?>


</body>

</html>