<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/user-homepage.css">
  <link rel="stylesheet" href="custom-css/simple-animation/homepage-animation.css">
  <link rel="icon" type="image/png" href="images/kiwi-big.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fruiting In a Mood- Peachy Mood No More &#128541;</title>
</head>

<body>
  <div class="container-fluid">
    <?php
    include 'navbar.php';
    ?>

    <div class="left-container container-fluid">
      <div class="row">
        <div class="col-lg-6 d-flex align-items-lg-start flex-column ps-5 pe-5 pt-4">
          <h2 class="intro-title mb-5">From orchard to your doorstep - our promise of quality</h2>
          <h5 class="mb-4 intro-prgh">
            FIM'S FRESH FROM THE ORCHARD FRUITS WILL SATISFY YOUR FRUITY CRAVINGS, SO TAKE A BREAK FROM
            THE HEAT AND RESFRESH, BECOME A <span><a href="signup.php" class="text-info text-decoration-underline">MEMBER NOW!</a></span>
          </h5>

          <h5 class="intro-prgh">
            FRUITING IN A MOOD'S PRODUCTS ARE ASSURED TO BE FRESH, HIGH QUALITY, AND AFFORDABLE IN PRICE.
            FIM'S PRODUCTS COME FROM OUR OWN FARM. FIM IS ONE OF THE BEST AND AFFORDABLE.
          </h5>

          <a href="fruits.php">
            <button class="custom-btn mt-3">HARVEST NOW</button>
          </a>
        </div>


        <div class="right-container col-lg-6 d-flex justify-content-lg-center align-items-lg-center flex-column">
          <img src="images/kiwi-big.png " alt="front-image" width="600px"
            class="front-image mb-5 object-fit-cover d-sm-none d-md-none d-lg-block d-xl-block d-none">
        </div>
      </div>
    </div>
  </div>
  <?php include('footer.php') ?>

</body>

</html>