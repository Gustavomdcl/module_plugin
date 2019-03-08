<?php 

add_shortcode('modelo_create','modelo_create_shortcode');
function modelo_create_shortcode($shortcode_args, $shortcode_content) {
	ob_start(); //inicio o "buffer" para salvar os dados que serão enviados
		$custom_post_type = 'modelo';
		$option = get_option($custom_post_type.'_options');//recupera os valores de modelo_options
		if($option['create']===true){
			$permission['admin'] = true;
		} else if($option['create']===false){
			$current_user_validation = wp_get_current_user();
			if($current_user_validation->ID!=0){
				$permission['admin'] = true;
			}
		} else if(is_array($option['create'])){
			foreach ($option['create'] as $i => $value) {
				if(get_user_roles($value)){
					$permission['admin'] = true;
				}
			}
		}
		if($permission['admin']==true){
			modelo_form(array(
				'type' => 'create'
			));
		} else {
			echo 'Sem permissão.';
			echo '<script>window.location.replace("'.home_url('login').'");</script>';
		}
	return ob_get_clean();
}