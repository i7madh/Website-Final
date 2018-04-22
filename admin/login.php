<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Website/Connection/conn.php';
include 'includes/head.php';

$email = ((isset($_POST['email'])) ?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password'])) ?sanitize($_POST['password']):'');
$password = trim($password);


$errors = array();

?>

<style>
#login-form {
   width: 320px ;
   height:  auto;
   top: 40% ;
   left: 50% ;
   position: absolute;
   transform: translate(-50%,-50%);
   box-sizing: border-box;

  }

   body{

     background-image: url("/Website/img/background.jpg");
     background-size: 100vw 100vh ;
   }

</style>

<div id="login-form" >
  <div>

  <?php
   if($_POST){
     //form validation
      if(empty($_POST['email']) || empty($_POST['password'])){
        $errors[] = 'You must provide email and password. ';
       }

        //validate email
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
          $errors[] = 'You must enter valid Email.' ;
        }

          // password is more than 6 charactors
          if (strlen($password)<6) {
            $errors[]= 'Password must be at least 6 Characters.';
          }
        // check if email exist in database
      $query = $db->query("SELECT * FROM users WHERE email = '$email'");
      $user = mysqli_fetch_assoc($query);
      $userCount = mysqli_num_rows($query);
      if ($userCount < 1) {
        $errors[] = 'That email doesn\'t Registr in our Store . ' ;
      }

     if (!password_verify($password, $user['password'])) {
       $errors [] = 'The password does not match with Email. Please try again.' ;
     }

      //check for errors
      if (!empty($errors)) {
        echo display_errors($errors);
      } else {
        // log user in
        $user_id = $user['id'];
        login($user_id);

      }
   }

   ?>

  </div>
  <h2 class="text-center">Login</h2> <hr>
  <form action="login.php" method="post">
    <div class="form-groub">
      <label for="email">Email:</label>
      <input type="text" name="email" id="email" class= "form-control" value="<?=$email;?>">
    </div>
    <div class="form-groub">
      <label for="password">Password:</label>
      <input type="password" name="password" id="password" class= "form-control" value="<?=$password;?>">
    </div>
    <div class="form-groub">
      <input type="submit" value="Login" class="btn btn-primary">
    </div>
  </form>
  <p class="text-right"><a href="/website/index.php" alt="home">Visit Store</a></p>
</div>


<?php include 'includes/footer.php'; ?>
