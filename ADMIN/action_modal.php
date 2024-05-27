
<!-- Delete -->
<div class="modal fade" id="delete<?php echo $row['productID']; ?>" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Delete Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <h5>Are sure you want to delete</h5>
            <h2>Name: <b><?php echo $row['productname']?></b></h2> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <a href="editprod.php?productid=<?php echo $row['productid']; ?>" class="btn btn-danger">Yes</a>
      </div>
    </div>
  </div>
</div>
 
<!-- Edit -->
<div class="modal fade" id="edit<?php echo $row['productid']; ?>" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel">Edit Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <form method="POST" action="productupdate.php?id=<?php echo $row['productid']; ?>">
                <div class="row">
                    <div class="col-lg-2">
                        <label style="position:relative; top:7px;">Product Name:</label>
                    </div>
                    <div class="col-lg-10">
                        <input type="text" name="firstname" class="form-control" value="<?php echo $row['productname']; ?>">
                    </div>
                </div>
                <div style="height:10px;"></div>
                <div class="row">
                    <div class="col-lg-2">
                        <label style="position:relative; top:7px;">Price:</label>
                    </div>
                    <div class="col-lg-10">
                        <input type="text" name="lastname" class="form-control" value="<?php echo $row['unitprice']; ?>">
                    </div>
                </div>
                <div style="height:10px;"></div>
                <div class="row">
                    <div class="col-lg-2">
                        <label style="position:relative; top:7px;">Measurement</label>
                    </div>
                    <div class="col-lg-10">
                        <input type="text" name="address" class="form-control" value="<?php echo $row['unitofmeasurement']; ?>">
                    </div>
                </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" name="edit" class="btn btn-warning">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>