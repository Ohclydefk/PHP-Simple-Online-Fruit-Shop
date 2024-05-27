<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/user-homepage.css">
  <link rel="icon" type="image/png" href="images/logo.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fruiting In a Mood- Peachy Mood No More &#128541;</title>
</head>

<body>
  <div class="container-fluid">
    <?php
      session_start();
      @include 'include scripts/db-connection.php';
    ?>


    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-6 d-flex align-items-lg-start flex-column ps-5 pe-5 pt-4">
          <h2 class="intro-title mb-5">Admin Management Login</h2>
          <h5 class="mb-4">
            FIM'S SYSTEMS <span>MANAGEMENT</span>
          </h5>

          <h5>
          <span>WARNING: </span> Access to this system is prohibited without authorization.
           Any unauthorized attempt to access, alter, or damage this system will be prosecuted to 
           the fullest extent of the law. If you are not an authorized user, please log out immediately and do not attempt 
           to access this system again. By continuing to access this system without authorization, you acknowledge that you may 
           be subject to civil and criminal penalties."
          </h5>
          
          <a href="admin.php">
            <button class="custom-btn mt-5">LOG IN</button>
          </a>
        </div>


        <div class="col-lg-6 d-flex justify-content-lg-center align-items-lg-center flex-column">
          <img src="images/adminn.png " alt="front-image" width="70%" class="front-image mb-5 object-fit-cover">
        </div>
      </div>
    </div>
  </div>

</body>

</html>