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
  <title>Fruiting In a Mood- ADMIN</title>
</head>

<body>
  <div class="container-fluid">
    <?php
    @include 'navbar.php';
    ?>


    <div class="container">
      <div class="row">
        <div class="container-fluid">
          <h2 class="intro-title text-center">Users Overview</h2>
          <?php include('user-info/user_table.php') ?>
          <br>
          <br>
        </div>
      </div>


      <div>
        <h2 class="mt-2 pt-5 intro-title mb-3 text-center">Product Overview</h2>
        <?php include('procduct-info/product_table.php') ?>

      </div>
    </div>
  </div>
</body>

</html>