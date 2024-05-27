<?php
$stmt = $conn->prepare("SELECT * FROM fruits WHERE productname LIKE :searchItem 
	ORDER BY productname");
$stmt->execute(['searchItem' => '%' . $term . '%']);

$search_result_set = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$search_count = count($search_result_set);
echo '<h5 class="result-set text-warning">Results Found: ' . $search_count . '</h5><br><br>';
?>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
  <?php foreach ($search_result_set as $row) { ?>
    <div class="col mb-4">
      <div class="customized-card card h-100 card">
        <?php if ($row['isBestSeller'] == "true"):
          echo '
                <div class="best-seller-container">
                  <img src="images/best-seller.png" width="100%" heigth="100%">
                  <p>Best Seller</p>
                </div>
              ';
        endif; ?>
        <img class="card-img-top customized-img" loading="lazy"
          src='data:image/jpeg;base64,<?php echo base64_encode($row['productimage']) ?>'
          alt="<?php echo $row['productname'] ?>">
        <div class="card-body customized-card-body">
          <h5 class="customized-title card-title mb-3">
            <?php echo $row['productname'] ?>
          </h5>

          <p class="card-text text-dark mb-2">
            Unit Price:
            <span class="text-success fw-bold">
              <?php echo '&#8369;' . $row['unitprice'] ?>
            </span>
            per
            <?php echo $row['unitofmeasurement'] ?>
          </p>

          <p class="card-text text-dark mb-4">
            Available:
            <span class="fw-bold <?php
            if ($row['stocks'] >= 100) {
              echo 'text-success';
            } elseif ($row['stocks'] >= 30) {
              echo 'text-warning';
            } else {
              echo 'text-danger';
            }
            ?>"> <?php echo $row['stocks'] ?></span>
          </p>
          <form action="basket.php" method="POST">
            <div class="d-flex flex-row align-items-center">
              <label for="quantity" class="form-label me-3 text-dark">Quantity:</label>
              <input type="number" class="form-control me-4 w-25 d-inline-block qty-input" name="quantity" value="1"
                min="1" max="15">
              <button type="submit"
                class="btn <?php echo ($row['stocks'] <= 0) ? 'btn-danger disabled' : 'btn-primary'; ?> d-inline-block"
                name="add-to-basket" <?php if ($row['stocks'] <= 0): ?> disabled <?php endif; ?>>
                <img src="images/shopping-cart.png" alt="shopping-cart" width="26px">
              </button>

            </div>
            <input type="hidden" name="get_id" value="<?php echo $row['productID'] ?>">
            <input type="hidden" name="get_img" value="<?php echo base64_encode($row['productimage']) ?>">
            <input type="hidden" name="get_productname" value="<?php echo $row['productname'] ?>">
            <input type="hidden" name="get_price" value="<?php echo $row['unitprice'] ?>">
            <input type="hidden" name="get_unit" value="<?php echo $row['unitofmeasurement'] ?>">
          </form>
        </div>
      </div>
    </div>
  <?php } ?>
</div>