<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="icon" type="image/png" href="images/kiwi-big.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Successful Transaction</title>
</head>

<body>
  <?php include('navbar.php') ?>

  <div class="container text-center">
    <h5 class="mb-3">
      Order Transaction Code:
      <span class="text-warning fw-bold text-decoration-underline">
        <?php echo 
        $_SESSION['current-trnx-no'];
        unset($_SESSION['current-trnx-no']); 
        
        ?>
      </span>
    </h5>

    <h5 class="mb-5">Thank you for your order! We hope you’ll love it. FIM will always love to serve you!❤️</h5>

    <h5>To check you order status, please click <a href="order_history.php" class="text-info text-decoration-underline">here.</a></h5>
  </div>




</body>

</html>