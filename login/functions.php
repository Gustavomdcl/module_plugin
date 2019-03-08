<?php

function get_error_message( $error_code ) {
    switch ( $error_code ) {
        case 'empty_username':
            return 'Usuário/Email não preenchido.';
 
        case 'empty_password':
            return 'Senha não preenchida.';
 
        case 'invalid_username':
            return 'Usuário/Email inválido.';
 
        case 'incorrect_password':
            $err = "Senha inválida. <a href='%s'>Esqueceu sua senha?</a>";
            return sprintf( $err,wp_lostpassword_url());

        // Lost password
		case 'empty_username':
		    return 'Informe um email para continuar.';
		 
		case 'invalid_email':
		case 'invalidcombo':
		    return 'O email informado não existe.';

		// Reset password
		case 'expiredkey':
		case 'invalidkey':
		    return 'O link para mudar a senha não é mais válido. Tente novamente.';
		 
		case 'password_reset_mismatch':
		    return 'As duas senhas informadas não são iguais.';
		     
		case 'password_reset_empty':
		    return 'Por favor não deixe os campos de senha vazios.';
 
        default:
            break;
    }
     
    return 'Erro. Por favor tente novamente';
} 

//LOGIN

	/* ===== LOGIN SPECIFIC REDIRECT ===== */

		add_filter('login_redirect','redirect_after_login',10,3);
		function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
		    $redirect_url = home_url();
		 
		    if ( ! isset( $user->ID ) ) {
		        return $redirect_url;
		    }
		 
		    if ( user_can( $user, 'manage_options' ) ) {
		        // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
		        if ( $requested_redirect_to == '' ) {
		            $redirect_url = admin_url();
		        } else {
		            $redirect_url = $requested_redirect_to;
		        }
		    } else {
		        // Non-admin users always go to their account page after login
		        $redirect_url = home_url( 'editar-perfil' );
		    }
		 
		    return wp_validate_redirect( $redirect_url, home_url() );
		}

	/* ===== LOGIN REDIRECT ===== */

		add_action('login_form_login','redirect_to_custom_login');
		function redirect_to_custom_login() {
		    if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
		        $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;
		     
		        if ( is_user_logged_in() ) {
		            redirect_logged_in_user( $redirect_to );
		            exit;
		        }

		        $login_url = home_url('login');
		        if ( ! empty( $redirect_to ) ) {
		            $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
		        }
		 
		        wp_redirect( $login_url );
		        exit;
		    }
		}

		/**
		 * Redirects the user to the correct page depending on whether he / she
		 * is an admin or not.
		 *
		 * @param string $redirect_to   An optional redirect_to URL for admin users
		 */
		function redirect_logged_in_user( $redirect_to = null ) {
		    $user = wp_get_current_user();
		    if ( user_can( $user, 'manage_options' ) ) {
		        if ( $redirect_to ) {
		            wp_safe_redirect( $redirect_to );
		        } else {
		            wp_redirect( admin_url() );
		        }
		    } else {
		        wp_redirect( home_url() );
		    }
		}

	/* ===== LOGIN VALIDATION ===== */

		add_filter( 'authenticate','maybe_redirect_at_authenticate',101,3);

		function maybe_redirect_at_authenticate($user, $username, $password) {
		    // Check if the earlier authenticate filter (most likely, 
		    // the default WordPress authentication) functions have found errors
		    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		        if (is_wp_error($user)) {
		            $error_codes = join(',', $user->get_error_codes());
		 
		            $login_url = home_url('login');
		            $login_url = add_query_arg('error',$error_codes,$login_url);
		 
		            wp_redirect($login_url);
		            exit;
		        }
		    }
		 
		    return $user;
		}

		function login_errors(){
			$errors = array();
			if (isset($_REQUEST['error'])) {
			    $error_codes = explode( ',', $_REQUEST['error'] );
			 
			    foreach ($error_codes as $code) {
			        $errors []= get_error_message( $code );
			    }
			}
			if (count($errors) > 0) {
				foreach ($errors as $error) { ?>
					<p class="login-error"><?php echo $error; ?></p>
				<?php }
			} 
		}

	/* ===== LOGOUT REDIRECT ===== */

		add_action('wp_logout','redirect_after_logout');
		function redirect_after_logout() {
		    $redirect_url = home_url('login?logged_out=true');
		    wp_safe_redirect($redirect_url);
		    exit;
		}

		function logout_message(){
			if(isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true) { ?>
				 <p class="login-info">Você foi desconectado. Gostaria de entrar novamente?</p>
			<?php }
		}

//PASSWORD

	/* ===== LOST PASSWORD REDIRECT ===== */

		add_action('login_form_lostpassword','redirect_to_custom_lostpassword');
		function redirect_to_custom_lostpassword() {
		    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
		        if (is_user_logged_in()){
		            redirect_logged_in_user();
		            exit;
		        }
		        wp_redirect(home_url('esqueci-senha'));
		        exit;
		    }
		}

	/* ===== LOST PASSWORD HANDLER ===== */

		add_action('login_form_lostpassword','do_password_lost');
		function do_password_lost() {
		    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
		        $errors = retrieve_password();
		        if ( is_wp_error( $errors ) ) {
		            // Errors found
		            $redirect_url = home_url('esqueci-senha');
		            $redirect_url = add_query_arg('error',join( ',', $errors->get_error_codes() ), $redirect_url );
		        } else {
		            // Email sent
		            $redirect_url = home_url('esqueci-senha');
		            $redirect_url = add_query_arg('checkemail','confirm',$redirect_url);
		        }
		 
		        wp_redirect( $redirect_url );
		        exit;
		    }
		}

	/* ===== LOST PASSWORD MESSAGES ===== */

		function lost_password_errors() {
			$errors = array();
			if ( isset( $_REQUEST['error'] ) ) {
			    $error_codes = explode( ',', $_REQUEST['error'] );
			    foreach ( $error_codes as $error_code ) {
			        $errors []= get_error_message( $error_code );
			    }
			}
			if ( count( $errors ) > 0 ) {
			    foreach ( $errors as $error ) { ?>
			        <p><?php echo $error; ?></p>
			    <?php }
			}
		}

		function lost_password_message() {
			if (isset($_REQUEST['checkemail'])&&$_REQUEST['checkemail']=='confirm') { ?>
			    <p class="login-info">Verifique no seu email o link para habilitar uma nova senha.</p>
			<?php }
		}

	/* ===== NEW PASSWORD REDIRECT ===== */

		add_action('login_form_rp','redirect_to_custom_password_reset');
		add_action('login_form_resetpass','redirect_to_custom_password_reset');
		function redirect_to_custom_password_reset() {
		    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
		        // Verify key / login combo
		        $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
		        if ( ! $user || is_wp_error( $user ) ) {
		            if ( $user && $user->get_error_code() === 'expired_key' ) {
		                wp_redirect( home_url( 'login?error=expiredkey' ) );
		            } else {
		                wp_redirect( home_url( 'login?error=invalidkey' ) );
		            }
		            exit;
		        }
		 
		        $redirect_url = home_url( 'nova-senha' );
		        $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
		        $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );
		 
		        wp_redirect( $redirect_url );
		        exit;
		    }
		}

	/* ===== NEW PASSWORD MESSAGES ===== */

		function new_password_errors() {
			$errors = array();
			if ( isset( $_REQUEST['error'] ) ) {
			    $error_codes = explode( ',', $_REQUEST['error'] );
			    foreach ( $error_codes as $error_code ) {
			        $errors []= get_error_message( $error_code );
			    }
			}
			if ( count( $errors ) >= 0 ) {
			    foreach ( $errors as $error ) { ?>
			        <p><?php echo $error; ?></p>
			    <?php }
			}
		}

		function new_password_update() {
			if (isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed') { ?>
			    <p class="login-info">Sua senha foi alterada. Você pode entrar em sua conta:</p>
			<?php }
		}

	/* ===== NEW PASSWORD HANDLER ===== */

		add_action('login_form_rp','do_password_reset');
		add_action('login_form_resetpass','do_password_reset');

		function do_password_reset() {
		    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
		        $rp_key = $_REQUEST['rp_key'];
		        $rp_login = $_REQUEST['rp_login'];
		 
		        $user = check_password_reset_key( $rp_key, $rp_login );
		 
		        if ( ! $user || is_wp_error( $user ) ) {
		            if ( $user && $user->get_error_code() === 'expired_key' ) {
		                wp_redirect( home_url( 'login?error=expiredkey' ) );
		            } else {
		                wp_redirect( home_url( 'login?error=invalidkey' ) );
		            }
		            exit;
		        }
		 
		        if ( isset( $_POST['pass1'] ) ) {
		            if ( $_POST['pass1'] != $_POST['pass2'] ) {
		                // Passwords don't match
		                $redirect_url = home_url( 'nova-senha' );
		 
		                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
		                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
		                $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );
		 
		                wp_redirect( $redirect_url );
		                exit;
		            }
		 
		            if ( empty( $_POST['pass1'] ) ) {
		                // Password is empty
		                $redirect_url = home_url( 'nova-senha' );
		 
		                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
		                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
		                $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );
		 
		                wp_redirect( $redirect_url );
		                exit;
		            }
		 
		            // Parameter checks OK, reset password
		            reset_password( $user, $_POST['pass1'] );
		            wp_redirect( home_url( 'login?password=changed' ) );
		        } else {
		            echo "Invalid request.";
		        }
		 
		        exit;
		    }
		}



