<?php
	require_once('database-connection/database-connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>simple image uploading website -- fullyworld web tutorials</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
	<style type="text/css" media="screen">
		.alert{
			position: absolute;
			z-index: 99999;
			right: 1%;
			top:10%;
            
		}
	</style>
</head>
<body>
	<div class="container-fluid">
		<?php
			if(isset($_GET['error'])){
		?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
			  <strong><?php echo $_GET['error']; ?></strong> 
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			  </button>
			</div>
		<?php
			}
		?>
		<?php
			if(isset($_GET['success'])){
		?>
			<div class="alert alert-success alert-dismissible fade show" role="alert">
			  <strong><?php echo $_GET['success']; ?></strong> 
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			  </button>
			</div>
		<?php
			}
		?>
		<!-- pload image modal box -->
		<div id="uploadImage" class="modal fade" role="dialog">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">

		        <h4 class="modal-title">Choose Image To Upload</h4>
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		      </div>
		      <div class="modal-body">
		        <form action="includes/imageUpload.php" method="post" enctype="multipart/form-data">
		        	<div class="form-group ">
		        		<input class="form-control" type="file" name="imageFile" />
		        	</div>
		        	<div class="form-group">
		        		<input type="submit" name="upload-image" class="btn btn-block btn-primary" value="Upload" />
		        	</div>
		        </form>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
		      </div>
		    </div>

		  </div>
		</div>

		<div class="row">
			<div class="col-md-12 bg-dark navigation">
				<nav class="navbar bg-dark">
					<a href="index.php" class="ml-5 text-white font-weight-bold navbar-brand logo">Pic Upload</a>
					<ul class="nav mr-5">
						<li class="nav-item"><a class="nav-link btn btn-outline-light" href="index.php" data-toggle="modal" data-target="#uploadImage">Upload</a></li>
					</ul>
				</nav>
			</div>
			<div class="row mt-5 mx-auto gallery">

				<?php

					$stmt = $conn->prepare("SELECT * FROM fruits");

					$stmt->execute();

					while($row  = $stmt->fetch()){
				?>
					<div class="card col-sm-4 p-0 my-1" >
					  <img class="card-img-top" src="uploads/<?php echo $row['productimage']; ?>" alt="<?php echo $row['productimage']; ?>">
					  <div class="card-body text-right">
					  	<form action="includes/deleteImage.php" method="post" accept-charset="utf-8">
					  		<input type="hidden" value="<?php echo $row['productID']; ?>" name="delete"/>
					  		<input class="btn btn-danger" value="Delete" type="submit"/>
					  	</form>

					  </div>
					</div>
				<?php
					}

				?>
		</div>
		</div>

	</div>




	<!-- script  -->
	<script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>