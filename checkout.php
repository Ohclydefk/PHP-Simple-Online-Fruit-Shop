<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/checkout-styles.css">
  <link rel="icon" type="image/png" href="images/kiwi-big.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fruiting In a Mood- Checkout</title>
</head>

<body>
  <?php
  include('navbar.php');

  $readOnly = false;
  $readOnly_val = "";

  if (isset($_SESSION['session-username'])) {
    $readOnly = true;

    if ($readOnly == true) {
      $readOnly_val = "readonly";
    } else {
      $readOnly_val = '';
    }
  }
  ?>

  <?php
  require_once('database-connection/database-connection.php');

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['session-userid'])) {
      if (isset($_POST['confirm-purchase'])) {
        include 'Validation_script/signup-validation.php';
        if (!empty($errorMessage)) {
          echo "<script>alert('" . $errorMessage . "'); window.location='checkout.php'</script>";
        } else {
          $get_fname = $_POST['fname'];
          $get_lname = $_POST['lname'];
          $get_telnum = $_POST['telnum'];
          $get_address = $_POST['address'];
          $get_city = $_POST['city'];
          $get_zip = $_POST['zip'];
          $get_email = $_POST['email'];
          $get_username = $_POST['username'];
          $get_password = $_POST['password'];

          $get_fname = filter_var($get_fname, FILTER_SANITIZE_STRING);
          $get_lname = filter_var($get_lname, FILTER_SANITIZE_STRING);
          $get_telnum = filter_var($get_telnum, FILTER_SANITIZE_STRING);
          $get_address = filter_var($get_address, FILTER_SANITIZE_STRING);
          $get_city = filter_var($get_city, FILTER_SANITIZE_STRING);
          $get_zip = filter_var($get_zip, FILTER_SANITIZE_STRING);
          $get_email = filter_var($get_email, FILTER_SANITIZE_EMAIL);
          $get_username = filter_var($get_username, FILTER_SANITIZE_STRING);
          $get_password = filter_var($get_password, FILTER_SANITIZE_STRING);

          $stmt = $conn->prepare("INSERT INTO customer_table (fname, lname, phonenumber, address, city, zip, email, username, password) 
        VALUES (:fname, :lname, :telnum, :address, :city, :zip, :email, :username, :password)");

          $passwordEncrypt = password_hash($get_password, PASSWORD_DEFAULT);

          $stmt->bindParam(':fname', $get_fname, PDO::PARAM_STR);
          $stmt->bindParam(':lname', $get_lname, PDO::PARAM_STR);
          $stmt->bindParam(':telnum', $get_telnum, PDO::PARAM_INT);
          $stmt->bindParam(':address', $get_address, PDO::PARAM_STR);
          $stmt->bindParam(':city', $get_city, PDO::PARAM_STR);
          $stmt->bindParam(':zip', $get_zip, PDO::PARAM_STR);
          $stmt->bindParam(':email', $get_email, PDO::PARAM_STR);
          $stmt->bindParam(':username', $get_username, PDO::PARAM_STR);
          $stmt->bindParam(':password', $passwordEncrypt, PDO::PARAM_STR);
          $stmt->execute();

          if ($stmt) {
            $get_user = $conn->prepare("SELECT * FROM customer_table WHERE username = :username");
            $get_user->bindValue(':username', $username);
            $get_user->execute();
            $validate_user = $get_user->fetch(PDO::FETCH_ASSOC);

            if ($validate_user) {
              if (password_verify($password, $validate_user['password'])) {
                $_SESSION['session-userid'] = $validate_user['uid'];
                $_SESSION['session-username'] = $validate_user['username'];
                $_SESSION['session-userfname'] = $validate_user['fname'];
                $_SESSION['session-userlname'] = $validate_user['lname'];
                $_SESSION['session-useremail'] = $validate_user['email'];
                $_SESSION['session-useraddress'] = $validate_user['address'];
                $_SESSION['session-userphonenum'] = $validate_user['phonenumber'];
                $_SESSION['session-userzip'] = $validate_user['zip'];
                $_SESSION['session-usercity'] = $validate_user['city'];
                $_SESSION['session-userstatus'] = $validate_user['status'];

                foreach ($_SESSION['cart'] as $product) {
                  $productname = $product['productname'];
                  $product_id = $product['productID'];
                  $uid = $_SESSION['session-userid'];
                  $unitprice = $product['unitprice'];
                  $productimage_string = $product['image'];
                  $quantity = $product['qty'];
                  $unitofmeasurement = $product['unit'];

                  $insert_to_basket = "INSERT INTO customer_basket (productname, product_id, uid, unitprice, productimage, quantity, unitofmeasurement) 
                  VALUES (:productname, :product_id, :uid, :unitprice, :productimage, :quantity, :unitofmeasurement)";

                  $insert = $conn->prepare($insert_to_basket);
                  $insert->bindParam(':productname', $productname);
                  $insert->bindParam(':product_id', $product_id);
                  $insert->bindParam(':uid', $uid);
                  $insert->bindParam(':unitprice', $unitprice);
                  $insert->bindParam(':productimage', $productimage_string, PDO::PARAM_LOB);
                  $insert->bindParam(':quantity', $quantity);
                  $insert->bindParam(':unitofmeasurement', $unitofmeasurement);
                  $insert->execute();
                }

                TransactionItems($conn);
              }
            }
          }
        }
      }
    } else {
      if (isset($_POST['confirm-purchase'])) {
        TransactionItems($conn);
      }
    }
  }

  function TransactionItems($conn)
  {
    $userid = $_SESSION['session-userid'];
    $totalprice = ceil($_SESSION['grandTotal']);
    $order_date = $_POST['order-date'];
    $dlvry_date = $_POST['dlvry-date'];
    $transaction_id = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_RIGHT);

    # Check if the transaction ID already exists
    while (true) {
      $sql = "SELECT * FROM transactions WHERE transaction_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$transaction_id]);

      if ($stmt->rowCount() > 0) {
        # If the transaction ID already exists, generate a new one and try again
        $transaction_id = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_RIGHT);

      } else {
        $_SESSION['current-trnx-no'] = 'TRNX-' .  $transaction_id;
        $uniqueTrnxID = $_SESSION['current-trnx-no'];
        # If the transaction ID doesn't exist, insert a new transaction record
        $new_transaction = "INSERT INTO transactions (transaction_id, transaction_date, delivery_date, transaction_total, uid, shipping_fee, delivery_fee, transaction_time) 
        VALUES (:TID, :TD, :DD, :TT, :UID, :SF, :DF, CURTIME())";
        $transaction_Query = $conn->prepare($new_transaction);
        $transaction_Query->bindParam(':TID', $uniqueTrnxID, PDO::PARAM_STR);
        $transaction_Query->bindParam(':TD', $order_date, PDO::PARAM_STR);
        $transaction_Query->bindParam(':DD', $dlvry_date, PDO::PARAM_STR);
        $transaction_Query->bindParam(':TT', $totalprice, PDO::PARAM_INT);
        $transaction_Query->bindParam(':UID', $userid, PDO::PARAM_STR);
        $transaction_Query->bindParam(':SF', $_SESSION['shippingFee'], PDO::PARAM_INT);
        $transaction_Query->bindParam(':DF', $_SESSION['deliveryFee'], PDO::PARAM_INT);
        $transaction_Query->execute();

        $user_basket_data = $conn->query("SELECT * FROM customer_basket WHERE uid = $userid");

        while ($row = $user_basket_data->fetch(PDO::FETCH_ASSOC)) {
          $PID = $row['product_id'];
          $get_fruit_price = $conn->query("SELECT unitprice FROM fruits WHERE productID = $PID");
          $fruit_price = $get_fruit_price->fetch()['unitprice'];

          $qty = $row['quantity'];
          $price = $row['unitprice'];
          $productname = $row['productname'];
          $unit = $row['unitofmeasurement'];
          $amount = $fruit_price * $qty;

          $stmt = $conn->prepare('INSERT INTO order_details (transaction_id, product_id, quantity, amount) 
            VALUES (:TID, :PID, :qty, :amount)');

          $deduct_fruit_qty = $conn->query("UPDATE fruits SET stocks = (stocks - $qty) WHERE productID = $PID");

          $stmt->bindValue(':TID', $uniqueTrnxID);
          $stmt->bindValue(':PID', $PID);
          $stmt->bindValue(':qty', $qty);
          $stmt->bindValue(':amount', ceil($amount));
          $stmt->execute();

          if ($stmt) {
            echo '<script>';
            $stmt = $conn->query("DELETE FROM customer_basket WHERE uid = $userid");
            echo 'alert("Thank you for your order!");';
            echo 'window.location.href =\'successful-transaction-page.php\';';
            echo '</script>';
          }
        }
        break;
      }
    }
  }
  ?>

  <div class="container">
    <div class="top-links align-items-center container-fuild mb-5 d-flex flex-row">
      <a href="basket.php">
        <h4 class="text-warning">Basket&nbsp;&nbsp;</h4>
      </a>

      <a href="checkout.php">
        <h4 class="text-warning">&#8594;&nbsp; Checkout</h4>
      </a>
      <?php
      if (!isset($_SESSION['session-username']) || !isset($_SESSION['session-userid'])) :
        echo '<small>&nbsp;&nbsp;&nbsp;Already have an account? 
                  <a href="login.php" class="text-info text-decoration-underline">Login Here.</a>
                </small>';
      endif;
      ?>
    </div>

    <form method="POST">
      <div class="upper-part row row-cols-lg-2">
        <div class="col pe-5">
          <h5 class="mb-5 text-info">Your Billing Information</h5>
          <div class="row align-items-center mb-2 row-gap-2">
            <div class="col-lg-4">
              <label for="name" class="form-label">Name</label>
            </div>

            <div class="col-lg-4">
              <input type="text" id="name" name="fname" class="form-control" placeholder="First Name" value="<?php if ($readOnly == true) : echo $_SESSION['session-userfname'];
                                                                                                              endif; ?>" <?php echo $readOnly_val ?> autocomplete="on" required>
            </div>

            <div class="col-lg-4">
              <input type="text" id="name" name="lname" class="form-control" placeholder="Last Name" value="<?php if ($readOnly == true) : echo $_SESSION['session-userlname'];
                                                                                                            endif; ?>" <?php echo $readOnly_val ?> autocomplete="on">
            </div>
          </div>

          <div class="row align-items-center mb-2 row-gap-2">
            <div class="col-lg-4">
              <label for="telnum" class="form-label">Phone Number</label>
            </div>

            <div class="col-lg-8">
              <input type="tel" id="telnum" name="telnum" class="form-control" placeholder="#######" value="<?php if ($readOnly == true) : echo $_SESSION['session-userphonenum'];
                                                                                                            endif; ?>" <?php echo $readOnly_val ?> autocomplete="on">
            </div>
          </div>

          <div class="row align-items-center mb-2 row-gap-2">
            <div class="col-lg-4">
              <label for="address" class="form-label">Address</label>
            </div>

            <div class="col-lg-8">
              <input type="text" class="form-control" name="address" id="address" cols="30" rows="1" <?php echo $readOnly_val ?> autocomplete="on" value="<?php if ($readOnly == true) : echo $_SESSION['session-useraddress'];
                                                                                                                                                          endif; ?>">
            </div>
          </div>

          <div class="row align-items-center mb-2 row-gap-2">
            <div class="col-lg-4">
              <label for="city" class="form-label">City</label>
            </div>

            <div class="col-lg-8">
              <input type="text" id="city" name="city" class="form-control" value="<?php if ($readOnly == true) : echo $_SESSION['session-usercity'];
                                                                                    endif; ?>" <?php echo $readOnly_val ?> autocomplete="on">
            </div>
          </div>


          <div class="row align-items-center mb-2 row-gap-2">
            <div class="col-lg-4">
              <label for="zip" class="form-label">Zip</label>
            </div>

            <div class="col-lg-8">
              <input type="text" id="zip" name="zip" class="form-control" value="<?php if ($readOnly == true) : echo $_SESSION['session-userzip'];
                                                                                  endif; ?>" <?php echo $readOnly_val ?> autocomplete="on">
            </div>
          </div>

          <div class="row align-items-center mb-2 row-gap-2">
            <div class="col-lg-4">
              <label for="email" class="form-label">Email</label>
            </div>

            <div class="col-lg-8 align-items-center">
              <input type="email" id="email" name="email" class="form-control" value="<?php if ($readOnly == true) : echo $_SESSION['session-useremail'];
                                                                                      endif; ?>" <?php echo $readOnly_val ?> autocomplete="on">
            </div>
          </div>

          <div class="row align-items-center mb-2 row-gap-2 
            <?php if (isset($_SESSION['session-userid'])) {
              echo 'd-none';
            } else {
              echo 'd-flex';
            } ?>">
            <div class="col-lg-4">
              <label for="username" class="form-label">Username</label>
            </div>

            <div class="col-lg-8">
              <input type="text" id="username" name="username" class="form-control" value="<?php if ($readOnly == true) : echo $_SESSION['session-username'];
                                                                                            endif; ?>" <?php echo $readOnly_val ?> autocomplete="on">
            </div>
          </div>
        </div>

        <div class="col">
          <!--Script to store the session values to variables-->
          <?php
          $Subtotal = number_format(ceil($_SESSION['subtotal_total']), 2);
          $DelFee = number_format(ceil($_SESSION['deliveryFee']), 2);
          $ShipFee = number_format(ceil($_SESSION['shippingFee']), 2);
          $Total = number_format(ceil($_SESSION['grandTotal']), 2);
          ?>

          <h5 class="mb-5 text-info">Your Order</h5>
          <div class="row align-items-center mb-2">
            <div class="col-lg-4">
              <label for="order-date" class="form-label">Order Date</label>
            </div>

            <div class="col-lg-8 align-items-center">
              <input type="date" name="order-date" class="form-control w-75" autocomplete="on" readOnly value="<?php echo date('Y-m-d'); ?>">
            </div>
          </div>

          <div class="row align-items-center mb-2">
            <div class="col-lg-4">
              <label for="dlvry-date" class="form-label">Delivery Date</label>
            </div>

            <div class="col-lg-8 align-items-center">
              <input type="date" name="dlvry-date" class="form-control w-75" autocomplete="on" readOnly value="<?php echo date('Y-m-d', strtotime('+8 days')); ?>">
            </div>
          </div>

          <div class="row align-items-center mb-2">
            <div class="col-lg-4">
              <label for="subtotal" class="form-label">Sub-Total</label>
            </div>

            <div class="col-lg-8 align-items-center">
              <input type="text" name="subtotal" value="<?php echo '&#8369;' . $Subtotal ?>" class="form-control w-75" autocomplete="on" required readOnly>
            </div>
          </div>

          <div class="row align-items-center mb-2">
            <div class="col-lg-4">
              <label for="deliveryfee" class="form-label">Delivery Fee</label>
            </div>

            <div class="col-lg-8 align-items-center">
              <input type="text" name="deliveryfee" value="<?php echo '&#8369;' . $DelFee ?>" class="form-control w-75" autocomplete="on" required readOnly>
            </div>
          </div>

          <div class="row align-items-center mb-2">
            <div class="col-lg-4">
              <label for="shippingfee" class="form-label">Shipping Fee</label>
            </div>

            <div class="col-lg-8 align-items-center">
              <input type="text" name="shippingfee" value="<?php echo '&#8369;' . $ShipFee ?>" class="form-control w-75" autocomplete="on" required readOnly>
            </div>
          </div>

          <div class="row align-items-center mb-2">
            <div class="col-lg-4">
              <label for="total" class="form-label">Total</label>
            </div>

            <div class="col-lg-8 align-items-center">
              <input type="text" name="total" value="<?php echo '&#8369;' . $Total ?>" class="form-control w-75 bg-success border-0" autocomplete="on" required readOnly>
            </div>
          </div>

          <div class="row align-items-center justify-content-center mt-5 ps-3">
            <button type="submit" name="confirm-purchase" class="w-25 btn btn-outline-info" onclick="return confirm('Do you want to proceed order?')">
              Confirm Order
            </button>
          </div>
        </div>
      </div>
      <?php if (isset($_SESSION['session-userid'])) : echo '</form>';
      endif; ?>


      <div class="mt-5 w-50 mb-5 <?php if (isset($_SESSION['session-userid'])) {
                                    echo 'd-none';
                                  } else {
                                    echo 'd-block';
                                  } ?>">
        <h5 class="text-info mb-3">Register</h5>
        <div class="row align-items-center mb-2">
          <div class="col-lg-4">
            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
          </div>

          <div class="col-lg-8 align-items-center">
            <input type="password" name="password" class="form-control w-75" autocomplete="on" required>
          </div>
        </div>

        <div class="row align-items-center mb-2">
          <div class="col-lg-4">
            <label for="confirm-password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
          </div>

          <div class="col-lg-8 align-items-center">
            <input type="password" name="confirm-password" class="form-control w-75" autocomplete="on" required>
          </div>
        </div>

        <div>
          <small class="text-warning">Password must contain atleast 1 uppercase, 1 special character, and 1 number.</small>
        </div>
      </div>
      <?php if (!isset($_SESSION['session-userid'])) : echo '</form>';
      endif; ?>
  </div>

</body>

</html>