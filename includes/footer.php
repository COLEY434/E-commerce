</div>

<footer class="text-center" id="footer">&copy; copyright <?php echo date('Y');?> Collins's Boutique</footer>
<br><br><br>


<script>
	jQuery(window).scroll(function(){
	  var vscroll = jQuery(this).scrollTop();
	  jQuery('#logotext').css({
	  	"transform" : "translate(0px, "+vscroll/2+"px)"
	  });
	   var vscroll = jQuery(this).scrollTop();
	  jQuery('#fore-flower').css({
	  	"transform" : "translate(0px, "+vscroll/4+"px)"
	  });

	});


	function detailsmodal(id) {
		var data = {"id" : id};
		jQuery.ajax({
			url : '/E-commerce/includes/detailsmodal.php',
			method : 'post',
			data : data,
			success: function(data) {
				jQuery('body').append(data);
				jQuery('#details-modal').modal('toggle');
			},
			error: function() {
				alert("something went wrong!");
			}
		});
	}	
	   
	   function update_cart(mode,edit_id,edit_size){
		var data = {"mode" : mode, "edit_id" : edit_id, "edit_size" : edit_size};
		jQuery.ajax({
			url : '/E-commerce/admin/parsers/update_cart.php',
			method : 'post',
			data : data,
			success : function(){
				location.reload();
			},
			error : function(){
				alert("oops something went wrong");
			}
		});

	}

	function add_to_cart(){
	jQuery('#modal-errors').html("");
	var size = jQuery('#size').val();
	var quantity = jQuery('#quantity').val();
	var available = jQuery('#available').val();
	var error = '';
	var data = jQuery('#add_product_form').serialize();
	
	if (size == '' || quantity == '' || quantity == 0){
		error += '<p class="text-center text-danger">You must select a size and quantity.</p>';
		jQuery('#modal-errors').html(error);
		return; 
	}
	else if(available < quantity){
		error += '<p class="text-center text-danger">There are only '+available+' available.</p>';
		jQuery('#modal-errors').html(error);
		return; 
	}
	else{
		jQuery.ajax({
			url : '/E-commerce/admin/parsers/add-cart.php',
			method : 'post',
			data : data,
			success : function(){
				location.reload();
			},
			error : function(){
				alert("something wwent wrong");
			},
		});
	 
	}
	}
	
</script>
</body>
</html>