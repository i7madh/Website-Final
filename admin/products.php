<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Website/Connection/conn.php';
if (!is_logged_in()) {
  loggin_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';

//Delete products
if (isset($_GET['delete'])) {
  $id = sanitize($_GET['delete']);
  $db->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
  header('Location: products.php');
}
$dbpath = '';
if(isset($_GET['add']) || isset($_GET['edit'])) {
$brandQuery = $db->query("SELECT * FROM brand ORDER BY brand") ;
$parentQuery =$db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
 //undefine varebale check
  $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
  $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
  $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
  $category = ((isset($_POST['child'])&& !empty($_POST['child']))?sanitize($_POST['child']):'');
  $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
  $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
  $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
  $type = ((isset($_POST['type']) && $_POST['type'] != '')?sanitize($_POST['type']):'');
  $type = rtrim($type,',');
  $saved_image = '';

  if(isset($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $productResults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
    $product = mysqli_fetch_assoc($productResults);
    if (isset($_GET['delete_image'])) {
      $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image']; echo $image_url ;
      unset($image_url);
      $db->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
      header('Location: products.php?edit='.$edit_id);
    }
    $category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$product['categories']);
    $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$product['title']);
    $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$product['brand']);
    $parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
    $parentResult = mysqli_fetch_assoc($parentQ);
    $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parentResult['parent']);
    $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$product['price']);
    $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):$product['list_price']);
    $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$product['description']);
    $type = ((isset($_POST['type']) && $_POST['type'] != '')?sanitize($_POST['type']):$product['type']);
    $type = rtrim($type,',');
    $saved_image = (($product['image'] != '')?$product['image']:'');
    $dbpath = $saved_image ;
  }
  if (!empty($type)) {
     $sizeString = sanitize($type);
     $sizeString = rtrim($sizeString,',');
     $sizesArray = explode(',',$sizeString);
     $sArray = array();
     $qArray = array();
     foreach ($sizesArray as $ss) {
       $s= explode(':', $ss);
       $sArray[]= $s[0];
       $qArray[]= $s[1];
     }

  }else{ $sizesArray= array();  }
if ($_POST) {

 $errors = array();
 $required = array('title', 'brand','price','parent','child','type');
 foreach($required as $field) {
    if($_POST[$field]=='') {
      $errors[]='All Fields with and Astrick are required.' ;
      break;
    }
  }
 if (!empty($_FILES)) {

    $photo = $_FILES['photo'];
    $name = $photo['name'];
    $nameArray = explode ('.',$name);
    $fileName =   $nameArray[0];
    $fileExt = $nameArray[1];
    $mime = explode('/',$photo['type']) ;
    $mimeType = $mime[0] ;
    $mimeExt = $mime[1];
    $tempLoc = $photo ['tmp_name'];
    $fileSize = $photo['type'];
    $allowed = array('png','jpg','jpeg','gif');
    $uploudName = md5(microtime()).'.'.$fileExt;
    $uploudPath = BASEURL.'img/'.$uploudName;
    $dbpath = '/Website/img/'.$uploudName;

    if ($mimeType != 'image') {
      $errors[]= 'The file must be an Image.' ;
    }
    if (!in_array($fileExt,$allowed)) {
        $errors [] = 'The photo must be a png , jpg , jpeg or gif . ';

    }
    if ($fileSize > 4194304 ) {
      $errors [] = 'The file size must be under 4 MB.';
    }
    if($fileExt != $mimeExt && ($mimeExt =='jpeg' && $fileExt !='jpg')){
      $errors[] = 'File extention does not match File.' ;
   }

 }
 if(!empty($errors)) {
   echo display_errors($errors);
  }else {
    //updloud file and insert into database
   if (!empty($_FILES)) {
       move_uploaded_file($tempLoc,$uploudPath) ;
   }

    $insertSql = "INSERT INTO products (`title`,`price`,`list_price`,`brand`,`categories`,`type`,`image`,`description`)
    VALUES ('$title','$price','$list_price','$brand','$category','$type','$dbpath','$description')";
    if(isset($_GET['edit'])){
        $insertSql = "UPDATE products SET title = '$title', price='$price', list_price = '$list_price',
        `brand` = '$brand', `categories` = '$category', `type` = '$type' , `image` = '$dbpath' , `description` = '$description'
        WHERE id= '$edit_id'";

    }

    $db->query($insertSql);
    header('Location: products.php');
  }
}
?>
 <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add a New');?> Product</h2> <hr>
 <form action="products.php?<?=((isset($_GET['edit']))?'edit=':'add=') ;?>" method="POST" enctype="multipart/form-data">
  <div class="form-groub col-md-3">
     <label for="title">Title*:</label>
      <input type="text" class="form-control" name="title" id="title" value="<?= $title; ?>">
  </div>
   <div class="form-groub col-md-3">
    <label for="brand">Brand*:</label>
      <select class="form-control" id="brand" name="brand">
         <option value=""<?= (($brand == '')?'selected':''); ?>></option>
          <?php while($b = mysqli_fetch_assoc($brandQuery)) : ?>
            <option value="<?= $b['id']; ?> "<?=(($brand == $b['id'])? 'selected':''); ?>><?=$b['brand']; ?></option>
           <?php endwhile ; ?>
      </select>
   </div>
    <div class="form-groub col-md-3">
      <label for="parent">Parent Category *:</label>
      <select class="form-control" id="parent" name="parent">
        <option value=""<?= (($parent =='')?'selected': ''); ?>></option>
         <?php while($p = mysqli_fetch_assoc($parentQuery))  : ?>
           <option value="<?=$p['id']; ?>"<?= (($parent == $p['id'])? 'selected': ''); ?>><?= $p['category'] ; ?></option>
          <?php endwhile; ?>
      </select>
    </div>
      <div class="form-groub col-md-3">
        <label for="child">Child Category *:</label>
         <select id="child" name="child" class="form-control"  >

         </select>
      </div>
      <div class="form-groub col-md-3">
         <label for="price">Price*:</label>
          <input class="form-control" id= "price" type="text" name="price" value="<?= $price; ?>">
      </div>
      <div class="form-groub col-md-3">
         <label for="list_price">List Price*:</label>
          <input class="form-control" id= "list_price" type="text" name="list_price" value="<?= $list_price; ?>">
      </div>

        <div class="form-groub col-md-3">
           <label>Quantity and Type *:</label>
          <button class= "btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Quantity & Type </button>

        </div>

       <div class="form-groub col-md-3">
         <label for="type">Type & Qunt. Preview</label>
         <input  type="text" class="form-control" name="type" id= "type" value="<?= $type ;?>" readonly>
       </div>

       <div class="form-groub col-md-6">
         <?php if($saved_image != '') : ?>
             <div class="saved-image">
              <img src="<?= $saved_image ; ?>"/> <br>
                <a href="products.php?delete_image=1&edit=<?= $edit_id; ?>" class="text-danger">Delete Image</a>
              </div>
         <?php else:  ?>
         <label for="photo">Product Photo: </label>
          <input type="file" name="photo" id="photo" class="form-control">
        <?php endif; ?>
       </div>
        <div class="form-groub col-md-6">
          <label for="description">Description: </label>
          <textarea id="description"name="description" class="form-control" rows="6" ><?= $description; ?></textarea>
        </div>

         <div class="row">
           <div class="form-groub pull-right">
              <a href="products.php" class="btn btn-default">Cancel</a>
               <input type="submit" class="btn btn-success " value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Product">
           </div>
         </div>
         <div class="clearfix">
         </div>
         <!-- Modal -->
        <div class="modal fade " id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true" >
          <div class="modal-dialog modal-lg" >
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="#sizesModalLabel">Type & Quantity </h4>
              </div>
              <div class="modal-body">
                <div class="container-fluid">


                <?php for($i=1; $i <= 2; $i++) : ?>
                  <div class="form-groub col-md-4">
                    <label for="type<?=$i; ?>">Type</label>
                    <input type="text" name="type<?=$i; ?>"id= "type<?= $i; ?>" value="<?= ((!empty($sArray[$i-1]))?$sArray[$i-1]:'') ;?>" class="form-control">
                       </div>

                    <div class="form-groub col-md-2">
                      <label for="qty<?=$i; ?>">Quantity</label>
                      <input type="number" name="qty<?=$i; ?>"id= "qty<?= $i; ?>" value="<?= ((!empty($qArray[$i-1]))?$qArray[$i-1]:'') ;?>" min="0" class="form-control">
                      </div>

                 <?php endfor ; ?>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;" >Save changes</button>
              </div>
            </div>
          </div>
        </div>

 </form>
<?php } else {
$sql = "SELECT * FROM products WHERE deleted = 0" ;
$presults = $db->query($sql);
if(isset($_GET['featured'])) {
$id = (int)$_GET['id'] ;
$featured = (int) $_GET['featured'];
$featuredSql = "UPDATE products SET featured = '$featured' WHERE id ='$id'";
$db->query($featuredSql);
header('Location: products.php');
}
?>
<div class="row">
<h2 class="text-center">Products</h2><hr>
<a href="products.php?add=1" class="btn btn-success" > Add Product </a><div class="clearfix"></div> <hr>
<table class="table table-bordered table-condensed table-striped">
 <thead><th></th><th>Product</th><th>Price</th><th>Category</th><th>Featured</th><th>Sold</th></thead>
  <tbody>
  <?php while($product = mysqli_fetch_assoc($presults)) :


   ?>
   <tr>
     <td>
       <a href="products.php?edit=<?=$product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
       <a href="products.php?delete=<?=$product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
     </td>
     <td><?=$product['title']; ?></td>
     <td><?=money($product['price']); ?></td>
     <td></td>
     <td><a href="products.php?featured=<?=(($product['featured']==0 )?1:'0');?>&id=<?=$product['id']; ?>" class="btn btn-xs btn-default ">
     <span class="glyphicon glyphicon-<?=(($product['featured']==1)?'minus':'plus'); ?>"></span>
     </a>&nbsp <?=(($product['featured'] ==1 )?'Featured Product':''); ?>
     </td>
     <td>0</td>
   </tr>
  <?php endwhile ; ?>
 </tbody>
</table>

</div>
<?php } include 'includes/footer.php'; ?>

<script type="text/javascript">
  jQuery('document').ready(function(){
  get_child_options(<?=$category ; ?>);

});
</script>
