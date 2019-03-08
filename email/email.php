<?php 

function module_send_mail($campos){
	/*module_send_mail(array(
		'to' => ,
		'subject' => ,
		'body' => ,
		'reply' => ,
		'cc' => ,
		'bcc' => 
	));*/
	//EMAIL =====
	$to = $campos['to'];
	$subject = $campos['subject'];
	$body = $campos['body'];
	$headers[] = 'Content-Type: text/html; charset=UTF-8';
	if(''!=$campos['reply']){
		array_push(
			$headers,
			'Reply-To: Nome Email <'.$campos['reply'].'>'
		);
	}
	if(''!=$campos['cc']){
		array_push(
			$headers,
			'Cc: <'.$campos['cc'].'>'
		);
	}
	if(''!=$campos['bcc']){
		array_push(
			$headers,
			'Bcc: <'.$campos['bcc'].'>'
		);
	}
	wp_mail($to, $subject, $body, $headers);
}

//https://wordpress.stackexchange.com/questions/75956/whats-the-easiest-way-to-setup-smtp-settings-programmatically
//https://gist.github.com/butlerblog/c5c5eae5ace5bdaefb5d
add_action( 'phpmailer_init', 'smtp_structure', 999 );
function smtp_structure( &$phpmailer ) {
    $from_name = 'Nome Email';
	$from_email = 'financeiro@agenciawebnauta.com.br';
    $phpmailer->IsSMTP();
	//$phpmailer->AddReplyTo( 'gustavomdcl@gmail.com', $from_name );
	$phpmailer->From = $from_email;
	$phpmailer->FromName = $from_name;
	$phpmailer->SetFrom( $phpmailer->From, $phpmailer->FromName );
	$phpmailer->SMTPSecure = 'tls';
	$phpmailer->Host = 'smtp.agenciawebnauta.com.br';
	$phpmailer->Port = 587;
	$phpmailer->SMTPAuth = true;
	$phpmailer->Username = 'financeiro@agenciawebnauta.com.br';
	$phpmailer->Password = 'p3Yon08$';
	$phpmailer->SMTPOptions = array(//precisou para funcionar no xampp ri-sos
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);
	//$phpmailer->SMTPAutoTLS = false;
}

/*add_action('init','email_test');
function email_test(){
	module_send_mail(array(
		'to' => 'gustavomdcl@gmail.com',
		'subject' => 'Teste',
		'body' => 'Este é um teste importante para entender a questão do smtp',
		'reply' => 'gustavomdcl@gmail.com'
	));
}*/