<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/basket-styles.css">
  <link rel="stylesheet" href="custom-css/simple-animation/basket-animation.css">
  <link rel="icon" type="image/png" href="images/kiwi-big.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fruiting In a Mood- Basket Overview</title>
</head>

<body>
  <?php
  include('navbar.php');
  require_once('database-connection/database-connection.php');

  if (isset($_POST['add-to-basket']) && !isset($_SESSION['session-userid'])) {
    $array_product_id = $_POST['get_id'];
    $quantity = $_POST['quantity'];

    $check_stocks = $conn->query("SELECT stocks FROM fruits WHERE productID = $array_product_id");
    $get_stocks = $check_stocks->fetch()['stocks'];

    if ($quantity > $get_stocks) {
      echo "<script>alert('❌ ERROR: Not enough stock available. Please choose a lower quantity.'); window.location='fruits.php';</script>";
      exit;
    } else {
    echo "<script>alert('Successfully Added to the Basket'); window.location='basket.php';</script>";

    $productArray = [
      "productname" => $_POST['get_productname'],
      "unitprice" => $_POST['get_price'],
      "unit" => $_POST['get_unit'],
      "image" => $_POST['get_img'],
      "qty" => $_POST['quantity'],
      "productID" => $_POST['get_id']
    ];




    # Check if cart array already exists in session, if not create it
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
      $_SESSION['get_ProductData'] = array();

    }

    # Check if product already exists in cart
    $productExists = false;
    foreach ($_SESSION['cart'] as $key => $product) {
      if ($product['productID'] == $productArray['productID']) {
        $_SESSION['cart'][$key]['qty'] += $productArray['qty'];
        $productExists = true;
        if ($_SESSION['cart'][$key]['qty'] > 15) {
          $_SESSION['cart'][$key]['qty'] = 15;
        }
        break;
      }
    }

    # Add product array to cart array if it doesn't exist
    if ($productExists == false or !$productExists) {
      $_SESSION['cart'][] = $productArray;
    }
  }

  # Delete Product
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete']) && !isset($_SESSION['session-userid'])) {
      $get_id = $_POST['get_id_val'];
      foreach ($_SESSION['cart'] as $key => $product) {
        if ($product['productID'] == $get_id) {
          echo "<script>alert('Successfully Removed from the Basket'); window.location='basket.php';</script>";
          unset($_SESSION['cart'][$key]);
          break;
        }
      }
      unset($_SESSION['get_ProductData'][$get_id]);
    }
  }
}



  # Update Product
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update']) && !isset($_SESSION['session-userid'])) {
      $get_new_qty = $_POST['basket-qty'];
      $get_id = $_POST['get_id_val'];

      $check_stocks = $conn->query("SELECT stocks FROM fruits WHERE productID = $get_id");
      $get_stocks = $check_stocks->fetch()['stocks'];
  
      if ($get_new_qty > $get_stocks) {
        echo "<script>alert('❌ ERROR: Not enough stock available. Please choose a lower quantity.'); window.location='basket.php';</script>";
        exit;
      }

      foreach ($_SESSION['cart'] as $key => $product) {
        if ($product['productID'] == $get_id) {
          if ($_SESSION['cart'][$key]['qty'] == $get_new_qty) {
            echo "<script>alert('No Changes. Please make sure that you input a new qunatity value.'); window.location='basket.php';</script>";
          } else {
            $_SESSION['cart'][$key]['qty'] = $get_new_qty;
            echo "<script>alert('Successfully Updated'); window.location='basket.php';</script>";
            break;
          }
        }
      }

      foreach ($_SESSION['get_ProductData'] as $key => $product) {
        if ($key == $get_id) {
          $_SESSION['get_ProductData'][$key]['qty'] = $get_new_qty;
          break;
        }
      }
    }
  }


  # Clearing the basket
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['clear-basket']) && !isset($_SESSION['session-userid'])) {
      $_SESSION['cart'] = array();
      $_SESSION['get_ProductData'] = array();
      header('Location: basket.php?Basket=Cleared');
    }
  }
  ?>

  <!-- Script for logged in users -->
  <?php
  # Add to basket conditions for logged in users.
  if (isset($_POST['add-to-basket']) && isset($_SESSION['session-userid'])) {
    $productname = $_POST['get_productname'];
    $product_id = $_POST['get_id'];
    $uid = $_SESSION['session-userid'];
    $unitprice = $_POST['get_price'];
    $productimage_string = $_POST['get_img'];
    $quantity = $_POST['quantity'];
    $unitofmeasurement = $_POST['get_unit'];

    $check_stocks = $conn->query("SELECT stocks FROM fruits WHERE productID = $product_id");
    $get_stocks = $check_stocks->fetch()['stocks'];

    if ($quantity > $get_stocks) {
      echo "<script>alert('❌ ERROR: Not enough stock available. Please choose a lower quantity.'); window.location='fruits.php';</script>";
      exit;
    }

    $stmt = $conn->prepare("SELECT * FROM customer_basket WHERE product_id = :product_id AND uid = :uid");
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':uid', $uid);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      echo "<script>alert('⚠ Product is already in the basket'); window.location='fruits.php';</script>";
      exit;
    }

    $insert_to_basket = "INSERT INTO customer_basket (productname, product_id, uid, unitprice, productimage, quantity, unitofmeasurement) 
      VALUES (:productname, :product_id, :uid, :unitprice, :productimage, :quantity, :unitofmeasurement)";

    $stmt = $conn->prepare($insert_to_basket);
    $stmt->bindParam(':productname', $productname);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':uid', $uid);
    $stmt->bindParam(':unitprice', $unitprice);
    $stmt->bindParam(':productimage', $productimage_string, PDO::PARAM_LOB);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':unitofmeasurement', $unitofmeasurement);

    $stmt->execute();

    echo "<script>alert('Successfully Added to the Basket 🧺'); window.location='basket.php';</script>";
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete']) && isset($_SESSION['session-userid'])) {
      $product_id = $_POST['get_id_val'];
      $uid = $_SESSION['session-userid'];

      $stmt = $conn->prepare('DELETE FROM customer_basket WHERE uid = :uid AND product_id = :product_id');
      $stmt->bindValue(':uid', $uid);
      $stmt->bindValue(':product_id', $product_id);
      $stmt->execute();

      if ($stmt) {
        echo "<script>alert('Successfully Removed from the Basket 🧺'); window.location='basket.php';</script>";
        exit;
      }
    }

    if (isset($_POST['update']) && isset($_SESSION['session-userid'])) {
      $product_id = $_POST['get_id_val'];
      $quantity = $_POST['basket-qty'];
      $uid = $_SESSION['session-userid'];

      $check_stocks = $conn->query("SELECT stocks FROM fruits WHERE productID = $product_id");
      $get_stocks = $check_stocks->fetch()['stocks'];
  
      if ($quantity > $get_stocks) {
        echo "<script>alert('❌ ERROR: Not enough stock available. Please choose a lower quantity.'); window.location='basket.php';</script>";
        exit;
      }

      $stmt = $conn->prepare('UPDATE customer_basket SET quantity = :quantity WHERE uid = :uid AND product_id = :product_id');
      $stmt->bindValue(':quantity', $quantity);
      $stmt->bindValue(':uid', $uid);
      $stmt->bindValue(':product_id', $product_id);
      $stmt->execute();

      if ($stmt) {
        echo "<script>alert('Successfully Updated the Quantity'); window.location='basket.php';</script>";
        exit;
      }
    }

    if (isset($_POST['clear-basket']) && isset($_SESSION['session-userid'])) {
      $uid = $_SESSION['session-userid'];

      $stmt = $conn->prepare('DELETE FROM customer_basket WHERE uid = :uid');
      $stmt->bindValue(':uid', $uid);
      $stmt->execute();

      if ($stmt) {
        echo "<script>alert('Successfully Cleared the Basket 🧺'); window.location='basket.php';</script>";
      }
    }
  }

  ?>

  <div class="container main-basket-container">
    <div class="title-n-btn-container row row-cols-lg-2 row-cols-xl-2 row-cols-1 ps-2 pe-2 align-items-center mb-5">
      <div class="col col-sm-12">
        <h4 class="thizTitle">FRUIT BASKET</h4>
      </div>

      <div class="col col-sm-12 d-flex align-items-center justify-content-lg-end justify-content-xl-end justify-content-xxl-end 
        justify-content-start ">
        <div class="me-3 mt-3 mt-md-0">
          <a href="fruits.php">
            <button class="btn btn-outline-info">&#8592; Fruits</button>
          </a>
        </div>
      </div>
    </div>

    <div class="container-fluid mb-5">
      <table class="custom-table table m-0 p-0 table-hover table-responsive">
        <thead>
          <th>
            <p>Product</p>
          </th>

          <th>
            <p>Quantity</p>
          </th>

          <th>
            <p>Action</p>
          </th>
        </thead>

        <tbody>
          <?php if (isset($_SESSION['session-userid'])) { ?>
            <?php
            $uid = $_SESSION['session-userid'];
            $stmt = $conn->prepare('SELECT COUNT(*) FROM customer_basket WHERE uid = :uid');
            $stmt->bindValue(':uid', $uid);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            $get_user_basket_data = $conn->query("SELECT * FROM customer_basket WHERE uid = $uid");



            if (isset($_SESSION['session-userid']) && $count > 0) {
              ?>
              <?php
              $_SESSION['subtotal_total'] = 0;
              $_SESSION['countQty'] = 0;
              while ($product = $get_user_basket_data->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                  <td>
                    <div class="product-img-container container row mb-3 mt-2">
                      <div class="basket-img-container col-lg-4 col-12">
                        <img src='<?php echo "data:image/jpeg;base64," . $product['productimage'] ?>'
                          alt="<?php echo $product['productname'] ?>" width="100%" height="100%">
                      </div>

                      <div class="col-lg-8 col-12">
                        <h5 class="mb-3">
                          <?php echo $product['productname'] ?>
                        </h5>

                        <p>
                          Subtotal:
                          <span class="mb-2 text-warning">
                            <?php $_SESSION['subtotal'] = $product['unitprice'] * $product['quantity'] ?>
                            <?php echo '&#8369;' . number_format($product['unitprice'], 2) ?>
                          </span>
                        </p>

                        <p>
                          Unit:
                          <span class="mb-2 text-warning">
                            <?php echo $product['unitofmeasurement'] ?>
                          </span>
                        </p>
                      </div>
                    </div>
                  </td>

                  <!--FORM FOR ACTIONS SUCH AS UPDATE AND DELETION-->
                  <?php Actions($product, 'quantity', 'product_id', 'unitprice') ?>
                </tr>


                <?php $_SESSION['subtotal_total'] += $_SESSION['subtotal'] ?>
              <?php } ?>

              <?php
              $_SESSION['deliveryFee'] = ($_SESSION['subtotal_total'] / 2) * 0.15;
              $_SESSION['shippingFee'] = ($_SESSION['subtotal_total'] / 2) * 0.4;

              #With 12% discount :) 
              $NoDiscountTotal = $_SESSION['subtotal_total'] + $_SESSION['deliveryFee'] + $_SESSION['shippingFee'];
              $_SESSION['grandTotal'] = $NoDiscountTotal;

              ?>
            <?php } else { ?>
              <h5 class="text-warning mb-4">There are currently no items in the basket.</h5>
            <?php } ?>

          <?php } else { ?>


            <?php
            if (!empty($_SESSION['cart']) && !isset($_SESSION['session-userid'])) {
              ?>
              <?php
              $_SESSION['subtotal_total'] = 0;
              $_SESSION['countQty'] = 0;
              foreach ($_SESSION['cart'] as $product) { ?>
                <tr>
                  <td>
                    <div class="product-img-container container row mb-3 mt-2">
                      <div class="basket-img-container col-lg-4 col-12">
                        <img src='<?php echo "data:image/jpeg;base64," . $product['image'] ?>'
                          alt="<?php echo $product['productname'] ?>" width="100%" height="100%">
                      </div>

                      <div class="col-lg-8 col-12">
                        <h5 class="mb-3">
                          <?php echo $product['productname'] ?>
                        </h5>

                        <p>
                          Subtotal:
                          <span class="mb-2 text-warning">
                            <?php $_SESSION['subtotal'] = $product['unitprice'] * $product['qty'] ?>
                            <?php echo '&#8369;' . number_format($product['unitprice'], 2) ?>
                          </span>
                        </p>

                        <p>
                          Unit:
                          <span class="mb-2 text-warning">
                            <?php echo $product['unit'] ?>
                          </span>
                        </p>
                      </div>
                    </div>
                  </td>

                  <!--FORM FOR ACTIONS SUCH AS UPDATE AND DELETION-->
                  <?php Actions($product, 'qty', 'productID', 'unitprice') ?>
                </tr>


                <?php $_SESSION['subtotal_total'] += $_SESSION['subtotal'] ?>
              <?php } ?>

              <?php
              $_SESSION['deliveryFee'] = ($_SESSION['subtotal_total'] / 2) * 0.15;
              $_SESSION['shippingFee'] = ($_SESSION['subtotal_total'] / 2) * 0.4;

              #With 12% discount :) 
              $NoDiscountTotal = $_SESSION['subtotal_total'] + $_SESSION['deliveryFee'] + $_SESSION['shippingFee'];
              $_SESSION['grandTotal'] = $NoDiscountTotal;

              ?>
            <?php } else { ?>
              <h5 class="text-warning mb-4">There are currently no items in the basket.</h5>
            <?php } ?>

          <?php } ?>
        </tbody>
      </table>


      <!--Redaclare the condition-->
      <?php if (!isset($_SESSION['session-userid'])) { ?>
        <?php if (!empty($_SESSION['cart'])) { ?>
          <div class="container-fluid d-flex justify-content-end mt-5 pe-5">
            <div class="w-25">
              <h5>
                Subtotal:
                <span class="text-warning">
                  <?php echo '&#8369;' . number_format($_SESSION['subtotal_total']) ?>
                </span>
              </h5>
              <hr>

              <h5>
                Delivery Fee:
                <span class="text-warning">
                  <?php echo '&#8369;' . number_format($_SESSION['deliveryFee']) ?>
                </span>
              </h5>
              <hr>

              <h5>
                Shipping:
                <span class="text-warning">
                  <?php echo '&#8369;' . number_format($_SESSION['shippingFee']) ?>
                </span>
              </h5>
              <hr>

              <h5>
                Total:
                <span class="total-price">
                  <?php echo '&#8369;' . number_format(round($_SESSION['grandTotal'], 2), 2) ?>
                </span>
              </h5>

              <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-2 row-cols-md-2 mt-4">
                <div class="col-4">
                  <form method="POST">
                    <button class="btn btn-outline-warning w-100" name="clear-basket">Clear</button>
                  </form>
                </div>

                <div class="col-8">
                  <form action="checkout.php">
                    <button class="btn btn-outline-info w-100">Checkout</button>
                  </form>
                </div>
              </div>

            </div>
          </div>
        <?php } ?>
      <?php } else {
        $uid = $_SESSION['session-userid'];
        $stmt = $conn->prepare('SELECT COUNT(*) FROM customer_basket WHERE uid = :uid');
        $stmt->bindValue(':uid', $uid);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
          ?>
          <div class="container-fluid d-flex justify-content-end mt-5 pe-5">
            <div class="w-25">
              <h5>
                Subtotal:
                <span class="text-warning">
                  <?php echo '&#8369;' . number_format($_SESSION['subtotal_total']) ?>
                </span>
              </h5>
              <hr>

              <h5>
                Delivery Fee:
                <span class="text-warning">
                  <?php echo '&#8369;' . number_format($_SESSION['deliveryFee']) ?>
                </span>
              </h5>
              <hr>

              <h5>
                Shipping:
                <span class="text-warning">
                  <?php echo '&#8369;' . number_format($_SESSION['shippingFee']) ?>
                </span>
              </h5>
              <hr>

              <h5>
                Total:
                <!-- <span>
                <s class=" text-light-emphasis">
                  <?php # echo '(&#8369;' . number_format(round($NoDiscountTotal, 2), 2) . ')' ?>
                </s>
              </span> -->
                <span class="total-price">
                  <?php echo '&#8369;' . number_format(round($_SESSION['grandTotal'], 2), 2) ?>
                </span>
              </h5>

              <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-2 row-cols-md-2 mt-4">
                <div class="col-4">
                  <form method="POST">
                    <button class="btn btn-outline-warning w-100" name="clear-basket">Clear</button>
                  </form>
                </div>

                <div class="col-8">
                  <form action="checkout.php">
                    <button class="btn btn-outline-info w-100">Checkout</button>
                  </form>
                </div>
              </div>

            </div>
          </div>
          <?php
        }

        ?>
        <?php
      }
      ?>

      <?php
      # Fucntion for updating and dleting products (for unlogged in and logged in users)
      function Actions($product, $array_value_qty, $array_value_id, $array_value_price) {
        ?>
        <!--FORM FOR ACTIONS SUCH AS UPDATE AND DELETION-->
        <form method="POST" class="update-delete-form">
          <td class="for-qty">
            <input type="number" name="basket-qty" value="<?php echo $product[$array_value_qty] ?>" min="1" max="15" required>
            <!--Count the total quantity of all products-->
            <?php $_SESSION['countQty'] += $product[$array_value_qty] ?>
          </td>

          <td class="for-btn">
            <input type="hidden" name="get_id_val" value="<?php echo $product[$array_value_id] ?>">
            <input type="hidden" name="get_price_val" value="<?php echo $product[$array_value_price] ?>">

            <div class="row row-cols-lg-2 row-cols-xl-2 row-cols-xxl-2 ps-5 pe-5">
              <div class="col d-flex align-items-center justify-content-lg-center justify-content-xl-center 
                      justify-content-xxl-center">
                <button type="submit" name="update" class="btn btn-success w-100">
                  UPDATE
                </button>
              </div>
              <div class="col d-flex align-items-center justify-content-lg-center justify-content-xl-center 
                      justify-content-xxl-center">
                <button type="submit" name="delete" class="btn btn-danger w-100">
                  DELETE
                </button>
              </div>
            </div>
          </td>
          <input type="hidden" value="<?php echo $product[$array_value_id] ?>">
        </form>

      <?php } ?>
    </div>
  </div>
  <?php include('footer.php') ?>
</body>

</html>