<?php
 require_once 'Connection/conn.php';
// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
 \Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:
$token = $_POST['stripeToken'];
// GET The rest of the post data
$full_name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$state = sanitize($_POST['state']);
$zip_code = sanitize($_POST['zip_code']);
$vat = sanitize($_POST['vat']);
$sub_total = sanitize($_POST['sub_total']);
$grand_total = sanitize($_POST['grand_total']);
$cart_id = sanitize($_POST['cart_id']);
$description = sanitize($_POST['description']);
$charge_amount = number_format($grand_total,2) * 100 ;
$metadata = array(
"cart_id" => $cart_id,
"vat" => $vat,
"sub_total" => $sub_total,
);

// Charge the user's card:
$charge = \Stripe\Charge::create(array(
  "amount" => $charge_amount,
  "currency" => CURRENCY,
  "description" => $description,
  "source" => $token,
  "receipt_email" => $email,
  "metadata"   => $metadata
));
$db-query("UPDATE cart SET paid = 1 WHERE id ='{$cart_id}'");
$db-query("INSERT INTO transactions
 (charge_id,cart_id,full_name,email,street,street2,city,state,zip_code,country,sub_total,vat,grand_total,description,txn_type) VALUES
 ('$charge->id','$cart_id','$full_name','$email','$street','$street2','$city','$state','$sub_total','$vat','$grand_total','$description','$charge->opject')");
 $domain = ($_SERVER['HTTP_HOST'] != 'localhost')? '.'.$_SERVER['HTTP_HOST']:false;
 setcookie(CART_COOKIE,'',1,"/",$domain,false);
 include 'include/head.php';
 include 'include/navigation.php';
 include 'include/headerfull.php';
 ?>
  <h1 class="text-center text-success">Thank You!</h1>
  <p>Your card has been successfully charged <?=money($grand_total);?>. You have been emailed a receipt </p>
  <p>Your receipt number is: <strong><?=$cart_id;?></strong></p>
 <?php
include 'include/footer.php';

?>
