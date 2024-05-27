<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/signup-styles.css">
  <link rel="icon" type="image/png" href="images/kiwi-big.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fruiting In a Mood- SIGN UP</title>
</head>

<body>
  <div class="box p-4 container-fluid">
    <h3 class="text-center mb-4">WELCOME TO FIM- BECOME A MEMBER NOW!</h3>
    <br>

    <form action="register.php" method="POST">
      <div class="row row-cols-2">
        <div class="col d-flex align-items-center justify-content-center">
          <img src="images/signup-image.png" alt="hello" width="440" class="welcome-img">
        </div>

        <div class="col">
          <div class="align-middle d-flex align-items-center mb-5">
            <h4 class="me-3">REGISTRATION</h4>
            <p>Already have an account? <span><a href="login.php" class="text-info text-decoration-underline">Login
                  Here!</a></span></p>
          </div>

          <div class="row row-cols-3 align-items-center mb-2">
            <div class="col-lg-3">
              <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            </div>

            <div class="col-lg-5">
              <input type="text" id="name" name="fname" class="input-feild form-control" placeholder="First Name" required autocomplete="on">
            </div>

            <div class="col-lg-4 ps-0">
              <input type="text" id="name" name="lname" class="input-feild form-control" placeholder="Last Name" required autocomplete="on">
            </div>
          </div>


          <div class="row align-items-center mb-2">
            <div class="col-lg-3">
              <label for="telnum" class="form-label">Contact No. <span class="text-danger">*</span></label>
            </div>

            <div class="col-lg-9">
              <input type="number" id="telnum" name="telnum" class="input-feild form-control" placeholder="#######" autocomplete="on" required>
            </div>
          </div>

          <div class="row align-items-center mb-2">
            <div class="col-lg-3">
              <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            </div>

            <div class="col-lg-9">
              <input type="email" id="email" name="email" class="input-feild form-control" required autocomplete="on">
            </div>
          </div>

          <div class="row row-cols-4 align-items-center mb-2">
            <div class="col-lg-3">
              <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
            </div>

            <div class="col-lg-3">
              <textarea class="input-feild form-control" name="address" id="address" cols="30" rows="1" autocomplete="on" placeholder="Full Address" required></textarea>
            </div>

            <div class="col-lg-3 p-0">
              <input type="text" id="city" name="city" class="input-feild form-control" autocomplete="on" placeholder="City" required>
            </div>

            <div class="col-lg-3">
              <input type="text" id="zip" name="zip" class="input-feild form-control" autocomplete="on" placeholder="Zip" required>
            </div>
          </div>


          <div class="row align-items-center mb-2 row-gap-2">
            <div class="col-lg-3">
              <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
            </div>

            <div class="col-lg-9">
              <input type="text" id="username" name="username" class="input-feild form-control" required autocomplete="on">
            </div>
          </div>


          <div class="row align-items-center">
            <div class="col-lg-3">
              <label class="form-label mb-4" for="password">Password <span class="text-danger">*</span></label>
            </div>

            <div class="col-lg-5">
              <input type="password" id="password" name="password" class="input-feild form-control" placeholder="Enter Password" required autocomplete="on"><br>
            </div>

            <div class="col-lg-4 ps-0">
              <input type="password" id="confirm-password" name="confirm-password" class="input-feild form-control" placeholder="Confirm" required autocomplete="on"><br>
            </div>
          </div>

          <div class="d-flex align-items-center justify-content-center">
            <small class="text-warning text-center">Password must contain atleast 1 uppercase, 1 special character, and 1 number.</small>
          </div>

          <div class="d-flex align-items-center justify-content-center mt-4">
            <button name="sign-up" type="submit" class="btn btn-danger w-50 d-inline-block">SIGN UP</button>
          </div>
        </div>
      </div>
    </form>

    <div class="align-items-center">
      <a href="index.php?action=home">
        <button class="btn btn-dark px-4">
          <img src="images/home-icon.png" alt="home-icon" width="24">
        </button>
      </a>
    </div>
</body>

</html>