<?php

//DISABLED ====
	add_role(
		'disabled',
		'Desabilitado',
		array(
			'read'				=> true, // true allows this capability
			'level_0'			=> true
		)
	);

	add_action('init','user_disabled_redirect');
	function user_disabled_redirect() {
		$custom_post_type = 'registration';
		$option = get_option($custom_post_type.'_options');
		$current_user = wp_get_current_user();
		if(!empty($_GET['user']) && !empty($_GET['activation'])){
			if(get_user_meta((int)$_GET['user'],'activation',true)==$_GET['activation']){
				$u = new WP_User((int)$_GET['user']);
				if(get_userdata((int)$_GET['user'])->roles[0]=='disabled'){
					$u->remove_role('disabled');
					$u->add_role($option['role']);

						$first_name = get_userdata((int)$_GET['user'])->first_name;
						$username = get_userdata((int)$_GET['user'])->user_login;

						//EMAIL =====
						validation_success_email(array(
							'user_id' => $_GET['user']
						));

					wp_redirect(get_site_url().'/validacao?status=success&email='.get_userdata((int)$_GET['user'])->user_email);
					exit;
				}
			}
		}
		if(get_user_roles('disabled')) {
			wp_logout();
			wp_redirect(get_site_url().'/validacao?disabled=true');
			exit;
		}
	}