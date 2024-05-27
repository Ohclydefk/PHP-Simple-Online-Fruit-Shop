<head>
  <link rel="stylesheet" href="custom-css/nav-header.css">
  <link rel="stylesheet" href="custom-css/simple-animation/navbar-animation.css">
</head>

<?php
function isCurrentPage($page)
{
  return (basename($_SERVER['PHP_SELF']) == $page);
}
?>


<?php session_start(); ?>

<nav class="navbar navbar-expand-lg fixed-top custom-navigation">
  <div class="container-fluid nav-container d-flex align-items-center">
    <a class="navbar-brand" href="#">
      <img src="images/logo.png" alt="logo-image" width="100%" class="logo-img m-0 p-0 me-3"
        title="Fruiting In a Mood: Satisfy your Peachy Cravings &#128541;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto ms-0">
        <li>
          <a class="left-side-links <?php if (isCurrentPage('index.php'))
            echo 'active-link'; ?>" href="index.php">Home</a>
        </li>

        <li class="nav-item">
          <a class="left-side-links <?php if (isCurrentPage('fruits.php'))
            echo 'active-link'; ?>" href="fruits.php">Fruits</a>
        </li>

        <li class="nav-item">
          <a class="left-side-links <?php if (isCurrentPage('search.php'))
            echo 'active-link'; ?>" href="search.php">Search</a>
        </li>

        <li class="nav-item">
          <a class="left-side-links <?php if (isCurrentPage('about.php'))
            echo 'active-link'; ?>" href="about.php">About</a>
        </li>

        <li class="nav-item">
          <a class="left-side-links last-link-basket <?php if (isCurrentPage('basket.php'))
            echo 'active-link'; ?>" href="basket.php">
            Basket
          </a>
          <p class="items-count bg-danger">
            <?php 
            if (!isset($_SESSION['session-userid'])) {
              if (!isset($_SESSION['cart'])) {
                echo 0;
              } else {
                echo count($_SESSION['cart']);
              }
            } else {
              require_once('database-connection/database-connection.php');

              $uid = $_SESSION['session-userid'];
              $stmt = $conn->prepare('SELECT COUNT(*) FROM customer_basket WHERE uid = :uid');
              $stmt->bindValue(':uid', $uid);
              $stmt->execute();
              $count = $stmt->fetchColumn();

              echo $count;
            }
            
            ?>
          </p>
        </li>

        <li class="nav-item <?php if (isset($_SESSION['session-username'])) { echo 'd-block'; } else { echo 'd-none'; }?>">
          <a href="order_history.php" class="left-side-links" title="Your order history">
            <img src="images/order-history.png" alt="order-history" width="30px" height="30px">
          </a>
        </li>
      </ul>

      <ul class="navbar-nav">
        <?php if (isset($_SESSION['session-username'])) { ?>
          <li class="nav-item">
            <p class="nav-link mb-0">Hello,
              <?php echo $_SESSION['session-userfname']; ?>
            </p>
          </li>

          <li class="nav-item">
            <a href="logout-user.php">
              <button class="custom-btn-click btn btn-outline-danger">Logout</button>
            </a>
          </li>
        <?php } else { ?>
          <li class="nav-item">
            <a href="login.php" class="me-3">
              <button class="custom-btn-click btn btn-outline-dark">Login</button>
            </a>
          </li>

          <li class="nav-item">
            <a href="signup.php">
              <button class="custom-btn-click btn btn-outline-dark">Sign Up</button>
            </a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>

<script>
  // Dropdown Toggler
  const dropdownToggle = document.querySelector(".navbar-toggler");
  const navbarCollapse = document.querySelector(".navbar-collapse");
  dropdownToggle.addEventListener("click", function () {
    navbarCollapse.classList.toggle("show");
  });
</script>