<?php 

add_action('init','create_registration_options');
function create_registration_options() {
	$custom_post_type = 'registration'; 
	$nome['modelo'] = array(
		'singular' => 'Modelo',
		'plural' => 'Modelos',
		'slug' => 'modelo',
		'slug-plural' => 'modelos',
	);
	$options_list = array( 
		array(
			'option' => $custom_post_type.'_options', 
			'value' => array( 
				'role' => 'subscriber',
				'fields' => array( 
						array(
							'type' => 'text', 
							'name' => $custom_post_type.'_name', 
							'required' => true, 
							'label' => 'Nome', 
							'placeholder' => 'Insira seu nome'
						),
						array(
							'type' => 'text', 
							'name' => $custom_post_type.'_phone', 
							'required' => true, 
							'label' => 'Telefone', 
							'placeholder' => 'Informe seu Telefone' 
						),
						array(
							'type' => 'text', 
							'name' => $custom_post_type.'_email', 
							'required' => true, 
							'label' => 'Email', 
							'placeholder' => 'Informe seu Email' 
						),
						array(
							'type' => 'text', 
							'name' => $custom_post_type.'_cpf', 
							'required' => true, 
							'label' => 'CPF', 
							'placeholder' => 'Informe seu CPF' 
						),
						array(
							'type' => 'text', 
							'name' => $custom_post_type.'_cnpj', 
							'required' => true, 
							'label' => 'CNPJ', 
							'placeholder' => 'Informe seu CNPJ' 
						),
						array(
							'type' => 'radio', 
							'name' => $custom_post_type.'_smoke', 
							'required' => true, 
							'label' => 'Fumante', 
							'value' => array( 
								'Sim',
								'Não'
							)
						),
						array(
							'type' => 'checkbox', 
							'name' => $custom_post_type.'_city', 
							'required' => true, 
							'label' => 'Cidades', 
							'value' => array( 
								'São Paulo',
								'Rio de Janeiro',
								'Belo Horizonte',
								'Natal',
								'Curitiba'
							)
						),
						array(
							'type' => 'select', 
							'name' => $custom_post_type.'_car', 
							'required' => true, 
							'label' => 'Carro', 
							'value' => array( 
								'Volvo',
								'Mercedes',
								'Audi'
							)
						),
						array(
							'type' => 'radio', 
							'name' => $custom_post_type.'_gender', 
							'required' => true, 
							'label' => 'Gênero', 
							'value' => array( 
								'Masculino',
								'Feminino',
								'Outros'
							)
						),
						array(
							'type' => 'textarea', 
							'name' => $custom_post_type.'_description', 
							'required' => true, 
							'label' => 'Descrição', 
							'placeholder' => 'Fale sobre você' 
						),
						array(
							'type' => 'file', 
							'name' => $custom_post_type.'_file', 
							'required' => true, 
							'label' => 'Arquivo', 
							'accept' => '*'	
						),
						array(
							'type' => 'file', 
							'name' => $custom_post_type.'_image', 
							'required' => true, 
							'label' => 'Imagem', 
							'accept' => 'image/*', 
							'image' => true
						),
						array(
							'type' => 'file', 
							'name' => $custom_post_type.'_audio', 
							'required' => true, 
							'label' => 'Audio', 
							'accept' => '.pdf' 
						),
						array(
							'type' => 'text', 
							'name' => $custom_post_type.'_age', 
							'required' => true, 
							'label' => 'Idade', 
							'placeholder' => 'Informe sua idade' 
						)
				),
				'list' => array(
					'fields' => array(
						$custom_post_type.'_image',
						$custom_post_type.'_name',
						$custom_post_type.'_smoke',
						$custom_post_type.'_city',
						$custom_post_type.'_description'
					),
					'view' => true
				),
				'pages' => array(
					array(
						'title' => 'Cadastro',
						'slug' => 'cadastro',
						'content' => '[custom_registration]'
					),
					array(
						'title' => 'Validação',
						'slug' => 'validacao',
						'content' => '[custom_validation]'
					),
					array(
						'title' => 'Editar Perfil',
						'slug' => 'editar-perfil',
						'content' => '[edit_registration]'
					),
					array(
						'title' => 'Buscar Perfil',
						'slug' => 'buscar-perfil',
						'content' => '[registration_list]'
					)
				),
				'logged' => array(
					'editar-perfil',
					'editar-'.$nome['modelo']['slug'],
					'criar-'.$nome['modelo']['slug']
				)
			)
		),
		array(
			'option' => $custom_post_type.'_admin', 
			'value' => array( 
				'fields' => array( 
					$custom_post_type.'_email_reply' => array(
						'type' => 'text',
						'required' => true,
						'label' => 'Email de Resposta', 
						'placeholder' => 'Informe o email de resposta',
						'result' => 'email@site.com' 
					),
					$custom_post_type.'_email_color' => array(
						'type' => 'color',
						'required' => true,
						'label' => 'Cor do Email', 
						'placeholder' => 'Informe a cor dos emails de cadastro',
						'result' => '#666666'
					)/*,
					$custom_post_type.'_smoke' => array(
						'type' => 'radio',
						'required' => true, 
						'label' => 'Fumante', 
						'value' => array( 
							'Sim',
							'Não'
						)
					),
					$custom_post_type.'_city' => array(
						'type' => 'checkbox',
						'required' => true, 
						'label' => 'Cidades', 
						'value' => array( 
							'São Paulo',
							'Rio de Janeiro',
							'Belo Horizonte',
							'Natal',
							'Curitiba'
						)
					),
					$custom_post_type.'_car' => array(
						'type' => 'select',
						'required' => true, 
						'label' => 'Carro', 
						'value' => array( 
							'Volvo',
							'Mercedes',
							'Audi'
						)
					),
					$custom_post_type.'_image' => array(
						'type' => 'file', 
						'required' => true, 
						'label' => 'Imagem', 
						'accept' => 'image/*', 
						'image' => true
					)*/
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