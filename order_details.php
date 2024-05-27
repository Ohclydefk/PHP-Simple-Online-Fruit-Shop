<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/order-details-styles.css">
  <link rel="icon" type="image/png" href="images/kiwi-big.png">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Details</title>
</head>

<body>
  <?php
  include('navbar.php');
  require_once('database-connection/database-connection.php');

  # Get the transaction id from the URL parameter
  $transaction_id = $_GET['trnx_id'];
  $userid = $_SESSION['session-userid'];

  # Get the user order_detaols
  $order_details = $conn->query("SELECT fruits.productname, fruits.unitprice, fruits.unitofmeasurement, order_details.quantity, order_details.amount 
    FROM fruits JOIN order_details ON fruits.productID = order_details.product_id WHERE order_details.transaction_id = '$transaction_id'");

  # Get user details
  $customer_details = $conn->query("SELECT * FROM accounts WHERE uid = $userid");

  # Get transaction details
  $transaction_details = $conn->query("SELECT * FROM transactions WHERE transaction_id = '$transaction_id'");

  ?>

  <div class="container">
    <div class="row row-cols-2 align-items-center">
      <div>
        <h4 class="orders-title">Order Details</h4>
      </div>

      <div class="d-flex justify-content-end">
        <a href="order_history.php" class="btn btn-md btn-outline-success">&#8592; Order History</a>
      </div>

    </div>
    <br>
    <br>

    <div class="container-fluid row row-cols-2 p-0">
      <div>
        <?php while ($row = $customer_details->fetch(PDO::FETCH_ASSOC)) { ?>
          <p class="mb-3">
            User: &nbsp;<span class="text-warning">
              <?php echo $row['username'] ?>
            </span>
          </p>

          <p class="mb-3">
            Fullname: &nbsp;<span class="text-warning">
              <?php echo $row['fname'] . ' ' . $row['lname'] ?>
            </span>
          </p>

          <p class="mb-3">
            Address: &nbsp;<span class="text-warning">
              <?php echo $row['address'] . ', ' . $row['zip'] ?>
            </span>
          </p>

          <p class="mb-3">
            Contact No: &nbsp;<span class="text-warning">
              <?php echo '+63' . $row['phonenumber'] ?>
            </span>
          </p>

          <p class="mb-3">
            Email: &nbsp;<span class="text-warning">
              <?php echo $row['email'] ?>
            </span>
          </p>

        <?php } ?>
      </div>

      <div>
        <div>
          <?php if ($row = $transaction_details->fetch(PDO::FETCH_ASSOC)) { ?>
            <p class="mb-3">
              Order Transaction ID: &nbsp;<span class="text-warning">
                <?php echo $row['transaction_id'] ?>
              </span>
            </p>

            <p class="mb-3">
              Order Date: &nbsp;<span class="text-warning">
                <?php echo $row['transaction_date'] ?>
              </span>
            </p>

            <?php
            if ($row['status'] != 'Delivered') {
              ?>

              <p class="mb-3">
                Expected Delivery Date: &nbsp;<span class="text-warning">
                  <?php echo $row['delivery_date'] ?>
                </span>
              </p>

              <p class="mb-3">
                Order Status: &nbsp;<span class="text-warning">
                  <?php echo $row['status'] ?>
                </span>
              </p>

            <?php } else { ?>

              <p class="mb-3">
                Order Status: &nbsp;<span class="text-warning">
                  <?php echo 'Delivered' ?>
                </span>
              </p>

            <?php } ?>

            <p class="mb-3">
              Order Total: &nbsp;<span class="text-warning fw-bold">
                <?php echo '&#8369;' . number_format($row['transaction_total']) ?>
              </span>
            </p>
          <?php } ?>
        </div>
      </div>
    </div>

    <table class="mt-5 custom-table table m-0 p-0 table-hover table-responsive">
      <thead class="text-center">
        <th class="text-start">
          <p>Product Name</p>
        </th>

        <th>
          <p>Unit Price</p>
        </th>

        <th>
          <p>Unit of Measurement</p>
        </th>

        <th>
          <p>Quantity</p>
        </th>

        <th>
          <p>Amount</p>
        </th>
      </thead>

      <tbody class="text-center">
        <?php
        while ($row = $order_details->fetch(PDO::FETCH_ASSOC)) {
          ?>
          <tr>
            <td class=" text-start">
              <p>
                <?php echo '&#8226; ' . $row['productname']; ?>
              </p>
            </td>

            <td>
              <p class="text-success">
                <?php echo '&#8369;' . number_format($row['unitprice']) ?>
              </p>
            </td>

            <td>
              <p>
                <?php echo $row['unitofmeasurement']; ?>
              </p>
            </td>

            <td>
              <p>
                <?php echo $row['quantity']; ?>
              </p>
            </td>

            <td>
              <p class="text-success fw-bold">
                <?php echo '&#8369;' . number_format($row['amount']) ?>
              </p>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

</body>

</html>