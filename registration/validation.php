<?php 

/* ===== THE SHORTCODE ===== */
add_shortcode( 'custom_validation', 'custom_validation_shortcode' );
function custom_validation_shortcode() {
	ob_start();
	$current_user_validation = wp_get_current_user();
	if($current_user_validation->ID!=0){
		echo '<script>window.location.replace("'.get_site_url().'");</script>';
	} else {
		if(!empty($_GET['status']) && $_GET['status']=='success' && !empty($_GET['email'])){
			echo '<p class="cr_message">Sua conta foi validada com sucesso pelo email '.$_GET['email'].'! Faça <a href="'.get_site_url().'/login/">Login</a> para utilizar sua conta.</p>';
		} else if(!empty($_GET['wait']) && $_GET['wait']=='true' && !empty($_GET['email'])){
			echo '<p class="cr_message">Um novo email de validação foi enviado para o email '.$_GET['email'].'!</p>';
		} else {
			if(!empty($_GET['disabled']) && $_GET['disabled']=='true'){
				echo '<p class="cr_message">Sua conta ainda não foi validada. Acesse a sua caixa de entrada do email cadastrado para validar sua conta.</p>';
			} else if(!empty($_GET['disabled']) && $_GET['disabled']=='none' && !empty($_GET['disabled_email'])){
				echo '<p class="cr_message">O endereço de email '.$_GET['disabled_email'].' não foi encontrado em nossa base de cadastrados. Você pode utilizá-lo para fazer um novo registro clicando em <a href="'.get_site_url().'/cadastro/">Cadastro</a></p>';
			} else if(!empty($_GET['role']) && $_GET['role']=='able' && !empty($_GET['email'])){
				echo '<p class="cr_message">O endereço de email '.$_GET['email'].' já foi validado. Faça <a href="'.get_site_url().'/login/">Login</a> para utilizar sua conta.';
			}
			?>
			<form id="custom_validation" action="" method="post" enctype="multipart/form-data">
				<p>Não recebeu o email de validação?</p>
				<p>Preencha o seu email cadastrado abaixo para receber a mensagem de validação na sua caixa de entrada e validar a sua conta:</p>
				<input type="text" name="email" value="" placeholder="Email Cadastrado">

				<input type="submit" name="submit" value="Enviar"/>
				<input type="hidden" name="action" value="custom_validation" />
			</form>
			<?php 
		}
	}

	return ob_get_clean();
}

/* ===== THE REGISTRATION CONTAINER ===== */
add_action('init','custom_validation_function');
function custom_validation_function() {
	if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "custom_validation") {
			//USAGE ==== print_r(get_user_meta($user_id));
			global $email, $first_name, $username;
			$email      =   sanitize_email( $_POST['email'] );
			$user = get_user_by('email', $email);
			if(empty($user)){
				wp_redirect(get_site_url().'/validacao?disabled=none&disabled_email='.$email);
				exit;
			} else {
				if(get_userdata($user->ID)->roles[0]=='disabled'){
					$first_name = $user->first_name;
					$username = $user->user_login;
					$activation = get_user_meta($user->ID,'activation',true);
					$user = $user->ID;

					//EMAIL =====
					validation_email(array(
						'user_id' => $user
					));
					wp_redirect(get_site_url().'/validacao?wait=true&email='.$email);
					exit;
				} else {
					wp_redirect(get_site_url().'/validacao?role=able&email='.$email);
					exit;
				}
			}
	}
}