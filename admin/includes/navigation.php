<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <a href="index.php" class="navbar-brand">Ezhalha .Administration Area</a>
    <ul class="nav navbar-nav">

      <!-- Menu items -->
  <li><a href="brands.php">Brands</a></li>
  <li><a href="categories.php">Categories</a></li>
  <li><a href="products.php">Products</a></li>

  <?php if(has_premission('admin')) : ?>
  <li><a href="users.php">Users</a></li>
  <?php endif ; ?>
<li class="dropdown">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?=$user_data['first']; ?>!
   <span class="caret"></span>
  </a>
  <ul class="dropdown-menu" role="menu">
    <li><a href="change_password.php">Change Password</a></li>
    <li><a href="logout.php">Log out</a></li>
  </ul>

</li>
  </div>
</nav>
