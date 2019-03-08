<?php 

/* ===== THE SHORTCODE ===== */
add_shortcode( 'edit_registration', 'edit_registration_shortcode' );
function edit_registration_shortcode() {
	ob_start();
	$custom_post_type = 'registration';
	$current_user = wp_get_current_user()->ID;
	if($current_user!=0){
		registration_form(array(
			'id' => $current_user,
			'type' => 'edit_'.$custom_post_type
		));
	} else {
		echo '<script>window.location.replace("'.get_site_url().'");</script>';
	}
	return ob_get_clean();
}