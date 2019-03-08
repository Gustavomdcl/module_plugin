<?php 

add_action('init','create_login_options');
function create_login_options() {
	$custom_post_type = 'login'; 
	$options_list = array( 
		array(
			'option' => $custom_post_type.'_options', 
			'value' => array(
				'pages' => array(
					array(
						'title' => 'Login',
						'slug' => 'login',
						'content' => '[custom_login]'
					),
					array(
						'title' => 'Esqueci Senha',
						'slug' => 'esqueci-senha',
						'content' => '[lost_password]'
					),
					array(
						'title' => 'Nova Senha',
						'slug' => 'nova-senha',
						'content' => '[new_password]'
					)
				)
			)
		)
	);

	$i = 0;
	for($i = 0; $i < count($options_list); ++$i){
		if(get_option($options_list[$i]['option'])==''){ 
			add_option( 
				$options_list[$i]['option'],
				$options_list[$i]['value']
			);
		}
	}
	
	update_option(
		$options_list[0]['option'],
		$options_list[0]['value']
	);
}