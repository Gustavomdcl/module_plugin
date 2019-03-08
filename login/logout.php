<?php 

add_action('init','custom_logout');
function custom_logout() {
	if(isset($_GET['logout'])&&$_GET['logout']=='true') {
		wp_logout();
	}
}