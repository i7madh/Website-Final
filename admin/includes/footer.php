

<br>
<div class="footer">
  <div class="col-md-12 text-center">&copy; Copyright 2017-2018 Ezhalha Online Store</div>
</div>


<script>
   function updateSizes() {
    var sizeString = '' ;
    for (var i=1 ; i<=2; i++) {
        if(jQuery('#type'+i)!= ''){
          sizeString += jQuery('#type'+i).val()+':' +jQuery('#qty'+i).val()+',';
       }
    }
     jQuery('#type').val(sizeString);
  }

  function get_child_options(selected) {
    if(typeof selected == 'undefined') {
      var selected = '' ;
     }
   var parentID = jQuery('#parent').val();
   jQuery.ajax( {
    url: '/Website/admin/parsers/child_categories.php',
    type: 'POST',
    data: {parentID : parentID, selected: selected},
    success: function(data) {
     jQuery('#child').html(data);
     },
    error: function(){alert("Something went wrong with child option.")},

}) ;
  }
  jQuery('select[name="parent"]').change(function(){
  get_child_options();
});
</script>

</body>

</html>
