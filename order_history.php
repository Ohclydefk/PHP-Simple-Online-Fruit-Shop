<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/order-history-styles.css">
  <link rel="icon" type="image/png" href="images/kiwi-big.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order History</title>
</head>

<body>
  <?php
  include('navbar.php');
  require_once('database-connection/database-connection.php');
  ?>

  <?php
  $uid = $_SESSION['session-userid'];
  $stmt = $conn->query("SELECT * FROM transactions WHERE uid = $uid ORDER BY transaction_date DESC, transaction_time DESC");

  if ($stmt->rowCount() == 0) {
    echo '<div class="container" style="margin-bottom: 750px">';
    echo '<h5 class="text-center text-warning">You have no recent orders.</h5>';
    echo '</div>';
  } else {
    ?>
    <div class="container table-display-container">
      <h4 class="recent-orders-title">Recent Orders</h4>
      <br>

      <table class="mt-3 custom-table table m-0 p-0 table-hover table-responsive">
        <thead class="text-center">
          <th>
            <p>Order Date</p>
          </th>

          <th>
            <p>Transaction Time</p>
          </th>

          <th>
            <p>Transaction ID</p>
          </th>

          <th>
            <p>Status</p>
          </th>

          <th>
            <p>Shipping Fee</p>
          </th>

          <th>
            <p>Delivery Fee</p>
          </th>

          <th>
            <p>Order Total</p>
          </th>



          <th>
            <p>Action</p>
          </th>
        </thead>

        <tbody class="text-center">
          <?php
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
              <td>
                <p>
                  <?php echo $row['transaction_date']; ?>
                </p>
              </td>

              <td>
                <p>
                  <?php
                  $transaction_time = $row['transaction_time'];

                  # format the time using the date() function
                  $formatted_time = date("g:iA", strtotime($transaction_time));

                  echo $formatted_time;
                  ?>
                </p>
              </td>

              <td>
                <p class="text-danger">
                  <?php echo $row['transaction_id']; ?>
                </p>
              </td>

              <td>
                <p>
                  <?php echo $row['status'] ?>
                </p>
              </td>

              <td>
                <p class="text-success">
                  <?php echo '&#8369;' . number_format($row['shipping_fee']) ?>
                </p>
              </td>

              <td>
                <p class="text-success">
                  <?php echo '&#8369;' . number_format($row['delivery_fee']) ?>
                </p>
              </td>

              <td>
                <p class="text-warning fw-bold">
                  <?php echo '&#8369;' . number_format($row['transaction_total']) ?>
                </p>
              </td>



              <td>
                <a href="order_details.php?trnx_id=<?php echo $row['transaction_id']; ?>"
                  class="btn btn-sm btn-success">View Details</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php } ?>
  <?php include('footer.php') ?>

</body>

</html>