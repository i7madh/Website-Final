<?php
require_once 'Connection/conn.php' ;
include 'include/head.php';
include "include/navigation.php" ;
include "include/headerfull.php" ;
include "include/leftbar.php" ;

if (isset($_GET['cat'])) {
  $cat_id = sanitize($_GET['cat']);
} else {
  $cat_id = '';
}

$sql = "SELECT * FROM products WHERE categories = '$cat_id'" ;
$productQ = $db->query($sql) ;
$category = get_category($cat_id);
 ?>

  <!-- Mian content-->
  <div class ="col-md-8">
    <div class="row">
  <h2 class="text-center"><?=$category['parent']. ' || ' . $category['child']; ?></h2>
  <?php while($product = mysqli_fetch_assoc($productQ)) : ?>

  <div class= "col-md-3 text-center">
    <h4> <?php echo $product['title']; ?> </h4>
    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" class="img-style">
    <p class="list-price text-danger">List price: <s>$<?php echo $product['list_price']; ?></s></p>
    <p class="price"> Our price: $<?php echo $product['price']; ?> </p>
    <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?php echo $product['id']; ?>)"> Details </button>


   </div>
 <?php endwhile; ?>
    </div>
  </div>


<?php

include 'include/rightbar.php' ;
include 'include/footer.php' ;

?>
