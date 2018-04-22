<div class="footer">
   <p>&copy; Copyright 2017-2018 Ezhalha Online Store</p>
</div>

<script>
jQuery(window).scroll(function() {
var vscroll = jQuery(this).scrollTop();
jQuery('#logotext').css( {
"transform" : "translate(0px , "+vscroll/2+"px)"
});
}) ;

function detailsmodal(id) {
var data = {"id" :id};
jQuery.ajax({
  url:'/Website/include/detailsmodal.php',
  method : 'POST',
  data : data,
  success : function (data){
    jQuery('body').append(data);
    jQuery('#details-modal').modal('toggle');
},
  error : function (){
  alert("something is wrong! ") ;
},
});
}

function update_cart(mode,edit_id,edit_type){
 var data = {"mode" : mode, "edit_id" : edit_id, "edit_type" : edit_type};
 jQuery.ajax( {
   url : '/tutorial/admin/parsers/update_cart.php',
   method : 'POST',
   data : data,
   success : function(){location.reload();},
   error : function(){alert("Something is Wrong?!");},
  });
}

function add_to_cart(){
 jQuery('#modal_errors').html("");
 var type = jQuery('#type').val();
 var quantity = jQuery('#quantity').val();
 var available = jQuery('#available').val();
 var error = '';
 var data = jQuery('#add_product_form').serialize();

if (type == '' || quantity == '' || quantity == 0 ) {
  error += '<p class="text-danger text-center"> You must choose a Type and Quantity.</p>';
  jQuery('#modal_errors').html(error);
  return;
  }
 else if(quantity > available) {
   error += '<p class="text-danger text-center"> There are only '+available+' available.</p>';
   jQuery('#modal_errors').html(error);
    return;
 }
 else {
       jQuery.ajax({
       url : '/Website/admin/parsers/add_cart.php',
       method : 'POST',
       data : data,
       success : function(){
       location.reload();
          },
       error : function(){alert("Something is wrong!?.");},
    });
  }
}

</script>
 </body>
</html>
