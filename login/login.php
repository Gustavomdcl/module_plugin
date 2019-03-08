<?php 

/* ===== THE SHORTCODE ===== */
add_shortcode( 'custom_login', 'custom_login_shortcode' );
function custom_login_shortcode() {
	ob_start();
	$custom_post_type = 'login';
	$current_user_validation = wp_get_current_user();
	if($current_user_validation->ID!=0){
		echo 'Você já está logado.';
		echo '<script>window.location.replace("'.get_site_url().'");</script>';
	} else {
		login_form(array(
			'type' => $custom_post_type.'_do'
		));

	}
	return ob_get_clean();
}