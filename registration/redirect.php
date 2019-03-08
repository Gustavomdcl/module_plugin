<?php 

/* ==== Redirect ==== */

		add_action('wp','registration_profile_redirect');
		function registration_profile_redirect() {
			$custom_post_type = 'registration';
			$option = get_option($custom_post_type.'_options');
			$thisSlug = basename(get_permalink());
			$userID = wp_get_current_user()->ID;
			if($userID==0){
				foreach ($option['logged'] as $key => $page) {
					if($thisSlug==$page){
						wp_redirect(home_url('login'));
						exit;
					}
				}
			}
		}