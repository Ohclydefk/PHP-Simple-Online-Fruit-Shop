<?php
require_once('database-connection/database-connection.php');

$stmt = $conn->prepare("SELECT productname, unitprice, unitofmeasurement, productimage FROM fruits");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

# Display Result set (fron beta 0.0 display functionality test)
# foreach ($results as $row) {
#   echo "<h3>" . $row['productname'] . "</h3>";
#   echo "<p>Price: $" . $row['unitprice'] . " per " . $row['unitofmeasurement'] . "</p>";
#   echo "<img src='data:image/jpeg;base64," . base64_encode($row['productimage']) . "' />";
# }
?>

<?php foreach ($results as $row) { ?>
      <div class="row custom-modified-row flex-column flex-lg-row">
        <div class="col image-container">
          <img loading="lazy" src='data:image/jpeg;base64,<?php echo base64_encode($row['productimage']) ?>'
            height="190px" width="100%" class="disp-img rounded rounded-3" title="<?php echo $row['productname'] ?>">
        </div>

        <div class="col d-flex flex-column mt-5 mt-lg-0">
          <div class="mb-5">
            <h4 class="itemname"><?php echo $row['productname'] ?></h4>
          </div>

          <div class="d-flex flex-row align-items-center mb-4 mt-3">
            <label for="quantity" class="form-label me-5">Quantity</label>
            <input type="number" class="qty-input form-control me-4 w-25 d-inline-block" id="quantity" name="quantity"
              value="1" min="1">
            <button type="submit" class="custom-btn btn btn-outline-info d-inline-block">Add to Basket</button>
          </div>

          <div>
            <p>Unit Price: <span class="text-warning">&#8369;<?php echo $row['unitprice'] ?>
              </span> per <?php echo $row['unitofmeasurement'] ?></p>
          </div>
        </div>
      </div>
    <?php } ?>