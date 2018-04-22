<?php

require_once '../Connection/conn.php' ;
if (!is_logged_in()) {
  loggin_error_redirect();
}
include 'includes/head.php' ;
include 'includes/navigation.php' ;
//get brands from database
$sql = "SELECT * FROM brand ORDER BY brand";
$results = $db->query($sql) ;
$errors = array();

//Delete Brands
if(isset($_GET['delete']) && !empty($_GET['delete'])) {
 $delete_id = (int)$_GET['delete'];
 $delete_id = sanitize($delete_id);
 $sql = "DELETE FROM brand WHERE id= '$delete_id' ";
 $db->query($sql);
 header('Location: brands.php') ;
}

//Edit Brand
if(isset($_GET['edit']) && !empty($_GET['edit'])) {
 $edit_id = (int)$_GET ['edit'] ;
 $edit_id = sanitize($edit_id);
 $sql2 = "SELECT * FROM brand WHERE id = '$edit_id'";
 $edit_result = $db->query($sql2);
 $eBrand = mysqli_fetch_assoc($edit_result);
}

//if add form is submitted
if(isset($_POST['add_submit'])) {
   $brand = sanitize($_POST['brand']) ;
  //check if brand is blank
  if($_POST['brand'] == '') {
   $errors[] .= 'You must enter a Brand! ' ;
  }
  // check if brand exists in Database
  $sql = "SELECT * FROM brand WHERE brand = '$brand' ";
  if(isset($_GET['edit'])){
 $sql = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'" ;
}
  $result= $db->query($sql);
  $count = mysqli_num_rows($result);

   if ($count > 0) {
  $errors[] .= $brand.' already exists. Please Chose another brand name.' ;
}

  //display errors
  if (!empty($errors)) {
    echo display_errors($errors) ;
  } else {
     //Add brand to database!
      $sql = "INSERT INTO brand (brand) VALUES ('$brand')" ;
      $db->query($sql);
      header('Location: brands.php') ;
 }
}

?>


<h2 class="text-center"> Brands </h2><hr>
<!-- Brand Form -->
<div>
<div class="text-center">
<form class="form-inline" action="brands.php<?php echo ((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method ="post">
  <div class ="form-groub">

    <?php
     $brand_value = '' ;
     if (isset($_GET['edit'])){
     $brand_value = $eBrand['brand'];
  }
    else {
     if(isset($_POST['brand'])) {
      $brand_value = sanitize($_POST['brand']) ;

      }
    }
      ?>

  <lable for="brand"> <?php echo ((isset($_GET['edit']))?'Edit':'Add a');?> Brand: </lable>
  <input type="text" name="brand" id="brand" class="form-control" value="<?php $brand_value; ?>">
  <?php if(isset($_GET['edit'])): ?>
    <a href="brands.php" class="btn btn-default">Cancle</a>

  <?php endif; ?>
  <input type="submit" name="add_submit" value="<?php echo ((isset($_GET['edit']))?'Edit':'Add');?> Brand" class="btn btn-success">
  </div>
</form>
</div>
</div> <hr>


<table class="table table-bordered table-striped table-auto table-condensed">
<thead>
<th></th><th>Brands</th><th></th>
</thead>
<tbody>
<?php while($brand= mysqli_fetch_assoc($results)) : ?>
 <tr>
  <td><a href="brands.php?edit=<?php echo $brand['id']; ?> " class="btn btn-xs btn-default" ><span class="glyphicon glyphicon-pencil"></span></a></td>
  <td><?php echo $brand['brand']; ?> </td>
  <td><a href="brands.php?delete=<?php echo $brand['id']; ?>" class="btn btn-xs btn-default" ><span class="glyphicon glyphicon-remove"></span></a></td>
 </tr>
<?php endwhile; ?>
</tbody>
</table> <br>
<?php include 'includes/footer.php' ; ?>
