<?php
require_once('database-connection/database-connection.php');
$stmt = $conn->prepare("SELECT * FROM fruits");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/search-styles.css">
  <link rel="stylesheet" href="custom-css/simple-animation/search-animation.css">
  <link rel="icon" type="image/png" href="images/kiwi-big.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fruiting In a Mood- Discover Products</title>
</head>

<body>
  <div class="container">
    <?php include('navbar.php') ?>

    <div class="searchbar-container">
      <h4 class="thizSearchTitle">The Ultimate Fruit Basket: Discover a wide variety of fuits</h4>

      <form action="search.php?action=search-fruits" method="POST">
        <div class="input-group custom-searchbar">
          <input name="keyword" type="text" class="form-control" placeholder="Discover our year's best fruit picks...">
          <button class="btn btn-primary" type="submit" name="search">Search</button>
        </div>
      </form>
    </div>

    <?php
    require_once('database-connection/database-connection.php');

    if (isset($_POST['search'])) {
      $term = $_POST['keyword'];

      if (empty($term)) {
        echo '<h4 class="error-msg text-warning">Please enter a keyword to search for fruits.</h4>';
      } else {
        include('search-product-script.php');

        if (count($search_result_set) == 0)
          echo '<h4 class="error-msg text-warning">No results found for your search term "' . $term . '"</h4>';
      }
    } else {
      ?>

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

    <?php
    }
    ?>
  </div>
  <?php include('footer.php') ?>







</body>

</html>