<!--For LOGIN Script :)-->
<?php
session_start();
require_once('database-connection/database-connection.php');

unset($_SESSION['cart']);

if (isset($_POST['sign-in'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $acc_login = $conn->prepare("SELECT * FROM accounts WHERE username = :username");
  $acc_login->bindValue(':username', $username);
  $acc_login->execute();
  $get_user = $acc_login->fetch(PDO::FETCH_ASSOC);

  if ($get_user) {
    if (password_verify($password, $get_user['password']) || $password == $get_user['password']) {
      if ($get_user['role'] == 'customer') {
        $_SESSION['session-userid'] = $get_user['uid'];
        $_SESSION['session-username'] = $get_user['username'];
        $_SESSION['session-userfname'] = $get_user['fname'];
        $_SESSION['session-userlname'] = $get_user['lname'];
        $_SESSION['session-useremail'] = $get_user['email'];
        $_SESSION['session-useraddress'] = $get_user['address'];
        $_SESSION['session-userphonenum'] = $get_user['phonenumber'];
        $_SESSION['session-userzip'] = $get_user['zip'];
        $_SESSION['session-usercity'] = $get_user['city'];
        $_SESSION['session-userstatus'] = $get_user['status'];

        if ($_SESSION['session-userstatus'] == "Blocked") {
          $_SESSION['message'] = "Your account has been blocked.";
          header('Location: login.php');
          exit;
        } elseif ($_SESSION['session-userstatus'] == "Deactivated") {
          $_SESSION['message'] = "Your account is inactive, contact administrator to activate your account.";
          header('Location: login.php');
          exit;
        } else {
          header('Location: index.php');
          exit;
        }
      } else {
        header('Location: ADMIN/adminUI.php');
      }
    } else {
      // Passwords do not match, show error message and redirect back to login page
      $_SESSION['message'] = "Incorrect username or password";
      header('Location: login.php');
      exit;
    }
  } else {
    // User not found in database, show error message and redirect back to login page
    $_SESSION['message'] = "Invalid Username or Password";
    header('Location: login.php');
    exit;
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/login-styles.css">
  <link rel="icon" type="image/png" href="images/kiwi-big.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fruiting In a Mood- WELCOME TO FIM</title>
</head>

<body>
  <div class="box py-3 container-fluid">
    <h3 class="text-center">WELCOME TO FRUITING IN A MOOD</h3>
    <br>
    <?php if (isset($_SESSION['message'])) { ?>
      <div class="alert alert-danger text-center">
        <?= $_SESSION['message']; ?>
      </div>
    <?php unset($_SESSION['message']);
    } ?>
    <div class="row row-cols-2">
      <div class="col d-flex align-items-center justify-content-center">
        <img src="images/welcome-login.png" alt="image" width="410">
      </div>

      <div class="col mt-5">
        <div class="d-flex align-items-center mb-5">
          <h4 class="me-4">LOGIN TO FIM</h4>

          <small>
            <p>Don't have an account?
              <span>
                <a href="signup.php" class="text-info text-decoration-underline">
                  Sign Up Here!
                </a>
              </span>
            </p>
          </small>
        </div>

        <div class="mt-5">
          <form action="login.php" method="POST">
            <label for="username" class="form-label mb-2">Username</label>
            <input type="text" id="username" name="username" class="inputbar form-control" placeholder="&#10000; Enter Username" required><br>

            <label for="password" class="form-label mb-2 mt-3">Password</label>
            <input type="password" id="password" name="password" class="inputbar form-control" placeholder="&#10148; Enter Password" required><br>

            <div class="container d-flex justify-content-center align-items-center mt-4 mb-8 p-0">
              <button name="sign-in" type="submit" class="btn btn-success d-inline-block me-6 w-50">SIGN IN</button>
            </div>
          </form>
        </div>

      </div>
    </div>

    <div class="mt-5 align-items-center">
      <a href="index.php?action=home">
        <button class="btn btn-danger px-4">
          <img src="images/home-icon.png" alt="home-icon" width="24">
        </button>
      </a>
    </div>




  </div>
</body>

</html>