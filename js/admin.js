jQuery(document).ready(function() {
	var height = jQuery(window).height() - jQuery('#wpadminbar').height() - jQuery('#wpfooter').height() - 80;
	jQuery('#bookings-frame').height(height);
});
