<?php
require_once 'Connection/conn.php' ;
include 'include/head.php';
include "include/navigation.php" ;
include "include/headerfull.php" ;
include "include/leftbar.php" ;

$sql = "SELECT * FROM Products WHERE featured = 1" ;
$featured = $db->query($sql) ;
 ?>


  <!-- Mian content-->
  <div class ="col-md-8">
    <div class="row">
  <h2 class="text-center">Feature Products</h2>
  <?php while($product = mysqli_fetch_assoc($featured)) : ?>

  <div class= "col-md-3 text-center">
    <h4> <?php echo $product['title']; ?> </h4>
    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>" class="img-style">
    <p class="list-price text-danger">List price: <s>$<?php echo $product['list_price']; ?></s></p>
    <p class="price"> Our price: $<?php echo $product['price']; ?> </p>
    <button type="button" class="btn btn-sm btn-success text-center" onclick="detailsmodal(<?php echo $product['id']; ?>)"> Details </button>


   </div>
 <?php endwhile; ?>
    </div> <hr>
  </div>



<?php

include 'include/rightbar.php' ;
include 'include/footer.php' ;



?>
