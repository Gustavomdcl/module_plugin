<?php 

/* ===== THE SHORTCODE ===== */
add_shortcode( 'lost_password', 'lost_password_shortcode' );
function lost_password_shortcode() {
	ob_start();
	$custom_post_type = 'login';
	$current_user_validation = wp_get_current_user();
	if($current_user_validation->ID!=0){
		echo 'Você já está logado.';
		echo '<script>window.location.replace("'.get_site_url().'");</script>';
	} else {
		lost_password_form(array(
			'type' => $custom_post_type.'_lost_password'
		));
	}
	return ob_get_clean();
}