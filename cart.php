<?php
require_once 'Connection/conn.php';
include 'include/head.php';
include 'include/navigation.php';
include 'include/headerfull.php';

 if ($cart_id != '') {
   $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
   $result = mysqli_fetch_assoc($cartQ);
   $items = json_decode($result['items'],true);
   $i = 1;
   $sub_total = 0 ;
   $item_count = 0 ;

 }
?>

<style >
  .cart{

    height:  auto;
    top: 60% ;
    left: 30% ;
    width: 620px ;
    height:  auto;
    position: relative;

  }
 .items{
   width: 170px

 }
 .totals-table-header th,tr{
   text-align:center;
 }

</style>

<div class="col-md-12">
  <div class="row">
    <div class="cart">


    <h2 class="text-center">My Shopping Cart</h2><hr>
     <?php if ($cart_id == ''): ?>
        <div class="bg-danger">
          <p class="text-center text-danger">Your Shopping cart is empty!</p>
        </div>
      <?php else : ?>
        <table class="table table-bordered table-condensed table-striped">
          <thead><th>#</th><th class="items">Product Name</th><th>Price</th><th>Quantity</th><th>Type</th><th>Sub Total</th></thead>
          <tbody>
            <?php
              if (is_array($items) || is_object($items)) {
              foreach($items as $item) {
                $product_id = $item['id'];
                $productQ = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
                $product = mysqli_fetch_assoc($productQ);
                $sArray = explode(',',$product['type']);
                foreach($sArray as $sizeString){
                    $s = explode(':',$sizeString);
                    if ($s[0] == $item['type']) {
                      $available = $s[1];

                    }
                  }
                    ?>
                  <tr>
                    <td><?=$i;?></td>
                    <td><?=$product['title']; ?></td>
                    <td><?=money($product['price']);?></td>
                    <td>
                      <button class="btn btn-xs btn-default" onclick="udpate_cart('removeone','<?=$product['id'];?>','<?=$item['type'];?>');">-</button>
                      <?=$item['quantity'];?>
                      <?php if ($item['quantity'] < $available): ?>
                            <button class="btn btn-xs btn-default" onclick="udpate_cart('addone','<?=$product['id'];?>','<?=$item['type'];?>');">+</button>
                      <?php else:  ?>
                        <span class="text-danger">MAX!</span>
                      <?php endif; ?>

                    </td>
                    <td><?=$item['type'];?></td>
                    <td><?=money($item['quantity'] * $product['price']); ?></td>
                  </tr>
          <?php
            $i++;
            $item_count += $item['quantity'];
            $sub_total += ($product['price'] * $item['quantity']);
             }
        }
              $vat = TAXRATE * $sub_total;
              $vat = number_format($vat,2);
              $grand_total = $vat + $sub_total;
              ?>

          </tbody>
        </table>

        <table class="table table-bordered table-condensed text-right">
          <legend>Totals.</legend>
          <thead class="totals-table-header"><th>Total items</th><th>Sub Total</th><th>VAT</th><th>Grand Total</th></thead>
          <tbody>
              <tr class="totals-table-header">
                <td><?=$item_count;?></td>
                <td><?=money($sub_total);?></td>
                <td><?=money($vat); ?></td>
                <td class="bg-success"><?=money($grand_total); ?></td>
              </tr>

          </tbody>
        </table>

            <!-- checkout button -->
    <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#checkoutModal">
      <span class="glyphicon glyphicon-shopping-cart"></span> CheckOut
    </button>

        <!-- Modal -->
      <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="checkoutModalLabel">Customers Information.</h4>
            </div>
            <div class="modal-body">
              <div class="row">


             <form action="thankYou.php" method="post" id="payment-form">
               <span class="bg-danger" id="payment-errors"></span>
                <input type="hidden" name="vat" value="<?=$vat;?>">
                <input type="hidden" name="sub_total" value="<?=$sub_total;?>">
                <input type="hidden" name="grand_total" value="<?=$grand_total;?>">
                <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
                <input type="hidden" name="description" value="<?=$item_count.' item'.(($item_count>1)?'s':'').' from ezhalha.';?>">
               <div id="step1" style="display:block;">
                 <div class="form-group col-md-6">
                   <label for="full_name">Full Name:</label>
                   <input class="form-control" id="full_name"  name="full_name" type="text">
                 </div>
                 <div class="form-group col-md-6">
                   <label for="email">Email:</label>
                   <input class="form-control" id="email"  name="email" type="email">
                 </div>
                 <div class="form-group col-md-6">
                   <label for="street">Address:</label>
                   <input class="form-control" id="street"  name="street" type="text" data-stripe="address_line1">
                 </div>
                 <div class="form-group col-md-6">
                   <label for="street2">Address 2:</label>
                   <input class="form-control" id="street2"  name="street2" type="text" data-stripe="address_line2">
                 </div>
                 <div class="form-group col-md-6">
                   <label for="city">City:</label>
                   <input class="form-control" id="city"  name="city" type="text" data-stripe="address_city">
                 </div>
                 <div class="form-group col-md-6">
                   <label for="state">State:</label>
                   <input class="form-control" id="state"  name="state" type="text" data-stripe="address_state">
                 </div>
                 <div class="form-group col-md-6">
                   <label for="zip_code">Zip Code:</label>
                   <input class="form-control" id="zip_code"  name="zip_code" type="text" data-stripe="address_zip">
                 </div>
                 <div class="form-group col-md-6">
                   <label for="country">Country:</label>
                   <input class="form-control" id="country"  name="country" type="text" data-stripe="address_country">
                 </div>

               </div>
               <div id="step2" style="display:none;">
                 <div class="form-group col-md-4">
                   <label for="name">Name on Card:</label>
                   <input type="text" id="name" class="form-control" data-stripe="name">
                 </div>
                 <div class="form-group col-md-6">
                   <label for="number">Card #Number:</label>
                   <input type="text" id="number" class="form-control" data-stripe="number">
                 </div>
                 <div class="form-group col-md-2">
                   <label for="cvc">CVC:</label>
                   <input type="text" id="cvc" class="form-control" data-stripe="cvc">
                 </div>
                 <div class="form-group col-md-3">
                   <label for="exp-month">Expire Month:</label>
                   <select id="exp-month" class="form-control" data-stripe="exp_month">
                     <option value=""></option>
                     <?php for($i=1;$i<13; $i++): ?>
                       <option value="<?=$i;?>"><?=$i;?></option>
                    <?php endfor;?>

                   </select>
                 </div>
                 <div class="form-group col-md-3">
                   <label for="exp-year">Expire Year:</label>
                   <select id="exp-year" class="form-control" data-stripe="exp_year">
                     <option value=""></option>
                     <?php $yr = date("Y"); ?>
                     <?php for($i=0;$i<11; $i++): ?>
                       <option value="<?=$yr+$i;?>"><?=$yr+$i;?></option>
                    <?php endfor;?>

                   </select>
                 </div>


               </div>

          </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">Next</button>
              <button type="button" class="btn btn-primary" onclick="back_address();" id="back_button" style="display:none;">Back</button>
              <button type="submit" class="btn btn-primary" id="check_out_button" style="display:none;" >CheckOut!</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php endif ?>
  </div>
 </div>
</div>

<script>
  function back_address(){
  jQuery('#payment-errors').html("");
  jQuery('#step1').css("display","block");
  jQuery('#step2').css("display","none");
  jQuery('#next_button').css("display","inline-block");
  jQuery('#back_button').css("display","none");
  jQuery('#check_out_button').css("display","none");
  jQuery('#checkoutModalLabel').html("Customer Information.");

}
  function check_address(){
  var data = {
    'full_name' : jQuery('#full_name').val(),
    'email' : jQuery('#email').val(),
    'street' : jQuery('#street').val(),
    'street2' : jQuery('#street2').val(),
    'city' : jQuery('#city').val(),
    'state' : jQuery('#state').val(),
    'zip_code' : jQuery('#zip_code').val(),
    'country' : jQuery('#country').val(),
  };
    jQuery.ajax({
      url: '/Website/admin/parsers/check_address.php',
      method: 'POST',
      data : data,
      success : function(data){
        if (data != 'Passed') {
          jQuery('#payment-errors').html(data);

        }
         if (data == 'Passed') {
            //clear errors if there is error message!
           jQuery('#payment-errors').html("");
           // submit
           jQuery('#step1').css("display","none");
           jQuery('#step2').css("display","block");
           jQuery('#next_button').css("display","none");
           jQuery('#back_button').css("display","inline-block");
           jQuery('#check_out_button').css("display","inline-block");
           jQuery('#checkoutModalLabel').html("Enter your Card Details");

         }
        },
      error : function(){alert("Something is Wrong*");},
  });

  }
   Stripe.setPublishableKey('<?=STRIPE_PUBLIC;?>');

  function StripeResponseHandler(status,response){
   var $form = $('#payment-form');

   if(response.error) {
      $form.find('#payment-errors').text(response.error.message);
      $form.find('button').prop('disabled',false);
      } else {
        var token = response.id;
        $form.append($('<input type="hidden" name="stripeToken" />').val(token));
        $form.get(0).submit();
      }
};



  jQuery(function($){
 $('#payment-form').submit(function(event){
 var $form = $(this);
 $form.find('button').prop('disabled',true);
 Stripe.card.createToken($form, StripeResponseHandler);
 return false;
 });
});


</script>

<?php include 'include/footer.php'; ?>
