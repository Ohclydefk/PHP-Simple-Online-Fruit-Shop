<?php
require_once('database-connection/database-connection.php');

if (isset($_REQUEST['btn_insert'])) {
  try {
    $name = $_REQUEST['txt_name'];
    $price = $_REQUEST['price'];
    $stocks = $_REQUEST['stocks'];

    $measurement = $_REQUEST['measurement'];
    $type = $_FILES["txt_file"]["type"]; //file name "txt_file"    
    $size = $_FILES["txt_file"]["size"];
    $temp = $_FILES["txt_file"]["tmp_name"];

    $path = "upload/" . $_FILES["txt_file"]["name"]; //set upload folder path

    if (empty($name) || empty($price) || empty($stocks) || empty($price) || empty($measurement)) {
      $errorMsg = "Please don't leave other fields blank.";
    } else if (empty($type)) {
      $errorMsg = "Please select an image.";
    } else if ($type == "image/jpg" || $type == 'image/jpeg' || $type == 'image/png' || $type == 'image/gif' || $type == 'image/webp') //check file extension
    {
      if (!file_exists($path)) //check file not exist in your upload folder path
      {
        if ($size < 6000000) //check file size 6MB
        {
          $fp = fopen($temp, 'r');
          $content = fread($fp, filesize($temp));
          fclose($fp);

          $insert_stmt = $conn->prepare('INSERT INTO fruits(productname,unitprice,unitofmeasurement,productimage,stocks) VALUES (:productname,:unitprice,:unitofmeasurement,:productimage,:stocks)'); //sql insert query
          $insert_stmt->bindParam(':productname', $name);
          $insert_stmt->bindParam(':stocks', $stocks);
          $insert_stmt->bindParam(':unitprice', $price);
          $insert_stmt->bindParam(':unitofmeasurement', $measurement);
          $insert_stmt->bindParam(':productimage', $content, PDO::PARAM_LOB);

          if ($insert_stmt->execute()) {
            $insertMsg = "Product successfully inserted!";
            header("refresh: 2.11; productmanagement.php");
          }
        } else {
          $errorMsg = "Your file is too large. Please upload a file of maximum 6MB size";
        }
      } else {
        $errorMsg = "File already exist. Please check your uploaded file.";
      }
    } else {
      $errorMsg = "Upload only JPG, JPEG, PNG, WEBP, and GIF file format. Double check your file extension.";
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="custom-css/general.css">
  <link rel="stylesheet" href="custom-css/add-styles.css">
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
  <title>Insert New Products</title>
</head>

<body>
  <div class="container add-container">
    <h3 class="text-dark mb-4 text-center">+ ADD A NEW PRODUCT HERE</h3>
    <?php
    if (isset($errorMsg)) {
      echo "<div class='alert alert-danger'><strong>$errorMsg</strong></div>";
    } else if (isset($insertMsg)) {
      echo "<div class='alert alert-success'><strong>$insertMsg</strong></div>";
    }

    ?>
    <form method="post" class="form-horizontal" enctype="multipart/form-data">

      <div class="row align-items-center mb-4">
        <div class="col-3">
          <label for="" class="text-dark"><strong>Product Name</strong></label>
        </div>

        <div class="col-8">
          <input type="text" name="txt_name" class="form-control" placeholder="&#10000; Product Name" />
        </div>
      </div>

      <div class="row mb-4 align-items-center">
        <div class="col-3">
          <label for="" class="text-dark"><strong>Product Price</strong></label>
        </div>

        <div class="col-8">
          <input type="text" name="price" class="form-control" placeholder="&#10000; Product Price" />
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-3">
          <label for="" class="text-dark"><strong>Measurement</strong></label>
        </div>

        <div class="col-8">
          <select class="form-control status" name="measurement">
            <option value="Pcs">🦎 Pcs</option>
            <option value="Grams">🦎 Grams</option>
            <option value="Kilograms" selected>🦎 Kilograms</option>
          </select>
        </div>

      </div>

      <div class="row align-items-center mb-4">
        <div class="col-3">
          <label for="" class="text-dark"><strong>Product Supply</strong></label>
        </div>

        <div class="col-8">
          <input type="number" name="stocks" class="form-control" placeholder="&#10000; Stocks" />
        </div>
      </div>

      <div class="row align-items-center mb-4">
        <div class="col-3">
          <label for="" class="text-dark"><strong>Product Image</strong></label>
        </div>

        <div class="col-8">
          <input type="file" name="txt_file" class="form-control" />
        </div>
      </div>

      <div class="form-group mt-5 d-flex align-items-center justify-content-center">
        <input type="submit" name="btn_insert" class="btn btn-primary me-3 w-25" value="&#8628; Insert">
        <a href="productmanagement.php" class="btn btn-danger w-25">&#10008; Cancel</a>
      </div>

    </form>
  </div>
  </div>
</body>

</html>