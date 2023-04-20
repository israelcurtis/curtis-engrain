jQuery(document).ready(function($) {
	
	$( '#api-fetch' ).click( function (e) {
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: ajaxurl,
			data: {
				action: 'fetch_units',
			},
			dataType: 'html',
			success: function (data) {
				$('#status').html('<h3>Success! <a href="/wp-admin/edit.php?post_type=unit">View the Unit listings</a></h3>');
				$('#response').html(data);
			},
			error: function (data) {
				$('#status').html('<h3>Sorry! API import was unsuccessful. Please contact support!</h3>');
				$('#response').html(data);
			}
		}); 
	});

});