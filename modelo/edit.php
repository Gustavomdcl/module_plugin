<?php 

add_shortcode('modelo_edit','modelo_edit_shortcode');
function modelo_edit_shortcode($shortcode_args, $shortcode_content){
	ob_start(); //inicio o "buffer" para salvar os dados que serão enviados 
		$custom_post_type = 'modelo';
		$nome = array(
			'singular' => 'Modelo',
			'plural' => 'Modelos',
			'slug' => 'modelo',
			'slug-plural' => 'modelos',
		);
		$option = get_option($custom_post_type.'_options');//recupera os valores de modelo_options
		$modelo_id = $_GET['id'];
		$author = get_post_field('post_author',$modelo_id);
		$current_user = wp_get_current_user()->ID;
		if($author==$current_user){
			$permission['admin'] = true;
		}
		foreach ($option['list']['permission'] as $i => $value) {
			if(get_user_roles($value)){
				$permission['admin'] = true;
			}
		}
		if ($modelo_id!='' && $permission['admin']==true) {
			modelo_form(array(
				'type' => 'edit',
				'id' => $modelo_id
			));
		} else {
			echo 'Sem permissão para essa página.';
			echo '<script>window.location.replace("'.home_url($nome['slug-plural']).'");</script>';
		}
	return ob_get_clean(); //exclui o buffer de saída atual
}