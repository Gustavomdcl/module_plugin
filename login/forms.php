<?php 

function login_form($campos) { ?>
	<?php $custom_post_type = 'login'; ?>
	<?php login_errors(); ?>
	<?php logout_message(); ?>
	<?php new_password_update(); ?>
	<div class="login-form-container">
		<h2>Login</h2>
		<?php
			$args = array(
				'echo'           => true,
				'remember'       => true,
				'redirect'       => home_url(),
				'form_id'        => $custom_post_type.'_form',
				'id_username'    => $custom_post_type.'_username',
				'id_password'    => $custom_post_type.'_password',
				'id_remember'    => $custom_post_type.'_remember',
				'id_submit'      => $custom_post_type.'_submit',
				'label_username' => 'Usuário/Email',
				'label_password' => 'Senha',
				'label_remember' => 'Lembrar',
				'label_log_in'   => 'Login',
				'value_username' => '',
				'value_remember' => true
			);
			wp_login_form($args); 
		?>
		<a href="<?php echo wp_lostpassword_url(); ?>">Esqueci Senha</a>
	    <a href="<?php echo get_site_url(); ?>/cadastro/">Cadastrar</a> 
	</div><!-- .login-form-container -->
<?php }

function lost_password_form($campos) { ?>
	<?php $custom_post_type = 'login'; ?>
	<?php lost_password_errors(); ?>
	<?php lost_password_message(); ?>
	<div id="password-lost-form" class="widecolumn">
	    <h2>Esqueci Senha</h2>
	    <p>Informe seu Usuário/Email para receber um link que habilitará uma nova senha.</p>
	    <form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
	        <label for="user_login">Usuário/Email</label>
	        <input type="text" name="user_login" id="user_login"><br>
	        <input type="submit" name="submit" class="lostpassword-button" value="Enviar"/>
	    </form>
	</div><!-- #password-lost-form -->
<?php }

function new_password_form($campos) { ?>
	<?php $custom_post_type = 'login'; ?>
	<?php if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) { ?>
       	<?php    
       		$campos['login'] = $_REQUEST['login'];
            $campos['key'] = $_REQUEST['key'];
		?>
		<?php new_password_errors(); ?>
		<div id="password-reset-form" class="widecolumn">
			<h2>Nova Senha</h2>
		    <form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off">
		        <label for="pass1">Nova Senha</label>
		        <input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" /><br>
				
				<label for="pass2">Repetir Senha</label>
		        <input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" /><br>
		         
		        <p class="description"><?php echo wp_get_password_hint(); ?></p>
		         
		        <input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $campos['login'] ); ?>" autocomplete="off" />
		        <input type="hidden" name="rp_key" value="<?php echo esc_attr( $campos['key'] ); ?>" />
		        <input type="submit" name="submit" id="resetpass-button" class="button" value="Mudar Senha" />
		    </form>
		</div>
	<?php } else { ?>
            <p>Link inválido.</p>
    <?php } ?>
<?php }