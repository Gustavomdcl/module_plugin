<?php

add_filter( 'retrieve_password_message', 'replace_retrieve_password_message', 10, 4 );
function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
	$email = $user_data->data->user_email;
    $subject = 'Redefinição de senha';
	// ===== EMAIL MESSAGE =====
    $bodyMessage  = '<span style="font-family:&quot;Verdana&quot;,sans-serif;">Olá,</span><br>
    <br>
    <span style="font-family:&quot;Verdana&quot;,sans-serif;">Você solicitou uma mudança de senha para o usuário '.$user_login.'.</span><br>
    <br>
    <span style="font-family:&quot;Verdana&quot;,sans-serif;">Se solicitou por engano, ou se não solicitou mudança de senha, apenas ignore esse email.</span><br>
    <br>
    <span style="font-family:&quot;Verdana&quot;,sans-serif;">Para mudar a sua senha, clique no link a seguir: <a href="'.site_url("wp-login.php?action=rp&key=$key&login=".rawurlencode($user_login),'login').'" target="_blank">'.site_url("wp-login.php?action=rp&key=$key&login=".rawurlencode($user_login),'login').'</a></span><br>
    <br>
    <span style="font-family:&quot;Verdana&quot;,sans-serif;">Obrigado,</span><br>
    <span style="font-family:&quot;Verdana&quot;,sans-serif;">Equipe '.get_bloginfo('name').'</span>';
    module_send_mail(array(
		'to' => $email,
		'subject' => $subject,
		'body' => $bodyMessage/*,
		'reply' => $reply*/
	));
    //return $bodyMessage;
}