<?php
require_once('database-connection/database-connection.php');
?>
<div class="container">
  <div class="row">
    <div class="col-md-12 mt-4">
      <div class="card">
        <div class="card-header">
          <h3 class="text-dark">Products on Stocks</h3>
        </div>
        <div class="card-body">
          <table class="table table-responsive table-bordered table-striped">
            <thead class="text-center">
              <th class="text-start">Product ID</th>
              <th class="text-start">Product Name</th>
              <th>Price</th>
              <th>Measurement</th>
              <th>Image</th>
            </thead>
            <tbody>
              <?php
              $query = "SELECT * FROM fruits";
              $statement = $conn->prepare($query);
              $statement->execute();

              $statement->setFetchMode(PDO::FETCH_OBJ); //PDO::FETCH_ASSOC
              $result = $statement->fetchAll();
              if ($result) {
                foreach ($result as $row) {
              ?>
                  <tr class="text-center align-middle">
                    <td class="text-start fw-bold text-primary">
                      <?= $row->productID; ?>
                    </td>

                    <td class="text-start">
                      <?= $row->productname; ?>
                    </td>

                    <td>
                      <?php echo '&#8369;' . $row->unitprice; ?>
                    </td>

                    <td>
                      <?= $row->unitofmeasurement; ?>
                    </td>

                    <td>
                      <?= '<img src="data:productimage;base64,' . base64_encode($row->productimage) . '" alt="Image" style="width: 90px; height: 80px;" 
                        class="prod-img">' ?>
                    </td>


                  </tr>
                <?php
                }
              } else {
                ?>
                <tr>
                  <td colspan="3">No Record Found</td>
                </tr>
              <?php
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>