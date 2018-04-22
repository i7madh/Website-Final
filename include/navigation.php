<?php
$sql = "SELECT * FROM categories WHERE parent = 0 " ;
$pquery = $db->query($sql);

?>

 <nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <a href="index.php" class="navbar-brand">Ezhalha For Softwares</a>
    <ul class="nav navbar-nav">

<?php while($parent = mysqli_fetch_assoc($pquery)) : ?>

  <?php
  $parent_id = $parent['id'];

  $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
  $cquery = $db->query($sql2);
  ?>
      <!-- Menu items -->
      <li class = "dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent ['category']; ?> <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">

          <?php while($child = mysqli_fetch_assoc($cquery)) : ?>
          <li><a href="category.php?cat=<?=$child['id'];?>"><?php echo $child ['category'] ; ?> </a></li>
        <?php endwhile; ?>

          </ul>
      </li>
    <?php endwhile; ?>
      <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Shopping Cart</a></li>
    </ul>
  </div>
 </nav>