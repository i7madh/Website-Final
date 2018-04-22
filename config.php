<?php
define('BASEURL',$_SERVER['DOCUMENT_ROOT'].'/Website/') ;
define('CART_COOKIE','SBwi72UCKSJjwqqdvxe23');
define('CART_COOKIE_EXPIRE',time() + (86400 *30));
define('TAXRATE',0.050); //SALES VAT
define('CURRENCY','usd');
define('CHECKOUTMODE','TEST'); //Change test to LIVE whene REAdy

if(CHECKOUTMODE == 'TEST'){
  define('STRIPE_PRIVATE','sk_test_PyQfxNbyjqP6uX5xWy8g9g3t');
  define('STRIPE_PUBLIC','pk_test_6MWjAPj3IPf7PdDMgT6jxZBV');
 }
 if(CHECKOUTMODE == 'LIVE'){
   define('STRIPE_PRIVATE','');
   define('STRIPE_PUBLIC','');
  }
