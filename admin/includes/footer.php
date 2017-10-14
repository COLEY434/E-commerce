</div>

<footer class="text-center" id="footer">&copy; copyright <?php echo date('Y');?> Collins's Boutique</footer>
<br><br><br>

<script>

function get_child_options(selected){
	if (typeof selected === 'undefined') {
		var selected = '';
	}
	
	var parentID = jQuery('#parent_category').val();
		jQuery.ajax({
			url: '/E-commerce/admin/parsers/child_categories.php',
			type: 'POST',
			data: {parentID : parentID, selected : selected},
			success: function(data){
				jQuery('#child_category').html(data);
			},
			error: function(){alert("something went wrong with the child option.")},
		});

}

jQuery('select[name="parent_category"]').change(function() {
	get_child_options();
});
</script>
</body>
</html>



 