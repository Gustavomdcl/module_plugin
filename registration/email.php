<?php

function registration_success_email($campos) {
	$custom_post_type = 'registration';
	$option = get_option($custom_post_type.'_admin');

	$user_id = $campos['user_id'];
	$registration_id = get_user_meta($user_id,$custom_post_type.'_id',true);
	$data = date("d/m/Y H:i:s");
	$ip = getUserIP();

	$name = get_post_meta($registration_id,$custom_post_type.'_name',true);
	$email = get_userdata($user_id)->user_email;
	$activation = get_user_meta($user_id,'activation',true);
	$username = get_userdata($user_id)->user_login;

	$color = $option['fields'][$custom_post_type.'_email_color']['result'];
	$reply = $option['fields'][$custom_post_type.'_email_reply']['result'];
	$subject = 'Nova Inscrição';
	// ===== EMAIL MESSAGE =====
	$bodyMessage = '<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Olá <strong>'.$name.'</strong>,</span><br>
	<br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Agradecemos pela sua inscrição no site <a href="'.get_site_url().'" target="_blank">'.get_bloginfo('name').'</a>, seja bem vindo à nossa comunidade!</span><br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Para finalizar o seu cadastro você deve validar a sua conta.</span><br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Clique no link abaixo para validar sua conta:</span><br>
	<br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'"><a href="'.get_site_url().'?user='.$user_id.'&activation='.$activation.'" target="_blank">'.get_site_url().'?user='.$user_id.'&activation='.$activation.'</a></span><br>
	<br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Lembre-se, seu usuário é <strong>'.$username.'</strong>.</span><br>
	<br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Obrigado,</span><br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Equipe '.get_bloginfo('name').'</span>';

	module_send_mail(array(
		'to' => $email,
		'subject' => $subject,
		'body' => $campos['message'].$bodyMessage,
		'reply' => $reply
	));
}

function validation_success_email($campos) {
	$custom_post_type = 'registration';
	$option = get_option($custom_post_type.'_admin');

	$user_id = $campos['user_id'];
	$registration_id = get_user_meta($user_id,$custom_post_type.'_id',true);
	$data = date("d/m/Y H:i:s");
	$ip = getUserIP();

	$name = get_post_meta($registration_id,$custom_post_type.'_name',true);
	$email = get_userdata($user_id)->user_email;
	$activation = get_user_meta($user_id,'activation',true);
	$username = get_userdata($user_id)->user_login;

	$color = $option['fields'][$custom_post_type.'_email_color']['result'];
	$reply = $option['fields'][$custom_post_type.'_email_reply']['result'];
	$subject = 'Bem vindo ao '.get_bloginfo('name');
	// ===== EMAIL MESSAGE =====
	$bodyMessage = '<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Olá <strong>'.$name.'</strong>,</span><br>
	<br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Sua conta foi validada no site <a href="'.get_site_url().'" target="_blank">'.get_bloginfo('name').'</a>.</span><br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Lembre-se, seu usuário é <strong>'.$username.'</strong>.</span><br>
	<br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Obrigado,</span><br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Equipe '.get_bloginfo('name').'</span>';

	module_send_mail(array(
		'to' => $email,
		'subject' => $subject,
		'body' => $campos['message'].$bodyMessage,
		'reply' => $reply
	));
}

function validation_email($campos) {
	$custom_post_type = 'registration';
	$option = get_option($custom_post_type.'_admin');

	$user_id = $campos['user_id'];
	$registration_id = get_user_meta($user_id,$custom_post_type.'_id',true);
	$data = date("d/m/Y H:i:s");
	$ip = getUserIP();

	$name = get_post_meta($registration_id,$custom_post_type.'_name',true);
	$email = get_userdata($user_id)->user_email;
	$activation = get_user_meta($user_id,'activation',true);
	$username = get_userdata($user_id)->user_login;

	$color = $option['fields'][$custom_post_type.'_email_color']['result'];
	$reply = $option['fields'][$custom_post_type.'_email_reply']['result'];
	$subject = 'Validação';
	// ===== EMAIL MESSAGE =====
	$bodyMessage = '<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Olá <strong>'.$name.'</strong>,</span><br>
	<br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Foi solicitado o envio de validação pelo site <a href="'.get_site_url().'" target="_blank">'.get_bloginfo('name').'</a> para a sua conta. Se você não realizou esse pedido, por favor ignore esse email.</span><br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Para finalizar o seu cadastro você deve validar a sua conta.</span><br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Clique no link abaixo para validar sua conta:</span><br>
	<br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'"><a href="'.get_site_url().'?user='.$user_id.'&activation='.$activation.'" target="_blank">'.get_site_url().'?user='.$user_id.'&activation='.$activation.'</a></span><br>
	<br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Lembre-se, seu usuário é <strong>'.$username.'</strong>.</span><br>
	<br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Obrigado,</span><br>
	<span style="font-family:&quot;Verdana&quot;,sans-serif;color:'.$color.'">Equipe '.get_bloginfo('name').'</span>';

	module_send_mail(array(
		'to' => $email,
		'subject' => $subject,
		'body' => $campos['message'].$bodyMessage,
		'reply' => $reply
	));
}