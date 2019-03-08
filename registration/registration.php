<?php 

//Registration
//https://code.tutsplus.com/tutorials/creating-a-custom-wordpress-registration-form-plugin--cms-20968
//http://cubiq.org/front-end-user-registration-and-login-in-wordpress
//https://www.sitepoint.com/building-custom-login-registration-pages-wordpress/

//Meta Values
//https://codex.wordpress.org/Function_Reference/add_user_meta

//Imagem
//https://stackoverflow.com/questions/39254742/how-do-i-change-a-users-profile-photo-as-the-admin-on-wordpress
//https://wordpress.stackexchange.com/questions/105801/how-to-change-profile-picture-in-wordpress
//https://wordpress.org/support/topic/plugin-user-photo-front-end-upload/?replies=17

//Email
//https://developer.wordpress.org/reference/functions/wp_mail/
//https://codex.wordpress.org/Plugin_API/Filter_Reference/wp_mail

/* ===== THE SHORTCODE ===== */
add_shortcode( 'custom_registration', 'custom_registration_shortcode' );
function custom_registration_shortcode() {
	ob_start();
	$custom_post_type = 'registration';
	$current_user_validation = wp_get_current_user();
	if($current_user_validation->ID!=0){
		echo '<script>window.location.replace("'.get_site_url().'");</script>';
	} else {
		if(!empty($_GET[$custom_post_type.'_result']) && $_GET[$custom_post_type.'_result']=='true' && !empty($_GET[$custom_post_type.'_email'])){
			echo '<p class="cr_message">Seu cadastro foi realizado com sucesso! Sua conta ainda precisa ser validada. Acesse a sua caixa de entrada do email '.$_GET[$custom_post_type.'_email'].' cadastrado para validar sua conta. Não recebeu o email de validação? Olhe no SPAM na sua caixa de email ou <a href="'.get_site_url().'/validacao/">Clique Aqui</a> para receber novamente.</p>';
		} else if(!empty($_GET[$custom_post_type.'_result']) && $_GET[$custom_post_type.'_result']=='false' && !empty($_GET[$custom_post_type.'_email'])){
			echo '<p class="cr_message">O endereço de email '.$_GET[$custom_post_type.'_email'].' já foi cadastrado. Por favor utilize outro endereço de email.</p>';
		} else if(!empty($_GET[$custom_post_type.'_result']) && $_GET[$custom_post_type.'_result']=='taken' && !empty($_GET[$custom_post_type.'_login'])){
			echo '<p class="cr_message">O Login '.$_GET[$custom_post_type.'_login'].' já foi cadastrado. Por favor utilize outro login.</p>';
		}
		registration_form(array(
			'type' => 'create_'.$custom_post_type
		));

	}
	return ob_get_clean();
}