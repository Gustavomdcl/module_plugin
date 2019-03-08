<?php 
//função do wordpress com dois parametros, que tem o hook 'init' que determina o início do WordPress e o outro chama a função especificada
add_action('init','create_modelo_options');
//função que indica quais campos vou querer ter no formulário
function create_modelo_options() {
	$custom_post_type = 'modelo'; //variável que recebe a string modelo
	$nome = array(
		'singular' => 'Modelo',
		'plural' => 'Modelos',
		'slug' => 'modelo',
		'slug-plural' => 'modelos',
	);
	// list of options
	$options_list = array( // array para criar os campos que quero no formulário
		array(
			'option' => $custom_post_type.'_options', // valor do array que recebe o nome do post 
			'value' => array( // em value recebe um outro array com o valor 'fields'
				'create' => array(
					'admin'
				),//true = sem estar logado, false = logado, array = apenas roles específicos
				'fields' => array( // em fields recebe um outro array com o outro array dentro
						array(
							'type' => 'text', //indico que o type é text
							'name' => $custom_post_type.'_name', //indico que o name recebe o valor de 'modelo_name'
							'required' => true, // é obrigatório o preenchimento
							'label' => 'Nome', // o nome da label
							'placeholder' => 'Insira seu nome'// texto do placeholder
						),
						array(
							'type' => 'text', //indico que o type é text
							'name' => $custom_post_type.'_phone', //indico que o name recebe o valor de 'modelo_phone'
							'required' => true, // é obrigatório o preenchimento
							'label' => 'Telefone', //o nome da label
							'placeholder' => 'Informe seu Telefone' // texto do placeholder
						),
						array(
							'type' => 'text', //indico que o type é text
							'name' => $custom_post_type.'_email', //indico que o name recebe o valor de 'modelo_email'
							'required' => true, // é obrigatório o preenchimento
							'label' => 'Email', //o nome da label
							'placeholder' => 'Informe seu Email' // texto do placeholder
						),
						array(
							'type' => 'text', //indico que o type é text
							'name' => $custom_post_type.'_cpf', //indico que o name recebe o valor de 'modelo_cpf'
							'required' => true, // é obrigatório o preenchimento
							'label' => 'CPF', //o nome da label
							'placeholder' => 'Informe seu CPF' // texto do placeholder
						),
						array(
							'type' => 'text', //indico que o type é text
							'name' => $custom_post_type.'_cnpj', //indico que o name recebe o valor de 'modelo_cnpj'
							'required' => true, // é obrigatório o preenchimento
							'label' => 'CNPJ', //o nome da label
							'placeholder' => 'Informe seu CNPJ' // texto do placeholder
						),
						array(
							'type' => 'radio', //indico que o type é radio
							'name' => $custom_post_type.'_smoke', //indico que o name recebe o valor de 'modelo_smoke'
							'required' => true, // é obrigatório o preenchimento
							'label' => 'Fumante', //o nome da label
							'value' => array( // os valores que o radio terá
								'Sim',
								'Não'
							)
						),
						array(
							'type' => 'checkbox', //indico que o type é radio
							'name' => $custom_post_type.'_city', //indico que o name recebe o valor de 'modelo_smoke'
							'required' => true, // é obrigatório o preenchimento 
							'label' => 'Cidades', //o nome da label
							'value' => array( // os valores que o checkbox terá
								'São Paulo',
								'Rio de Janeiro',
								'Belo Horizonte',
								'Natal',
								'Curitiba'
							)
						),
						array(
							'type' => 'select', //indico que o type é a tag select
							'name' => $custom_post_type.'_car', //indico que o name recebe o valor de 'modelo_car'
							'required' => true, // é obrigatório o preenchimento 
							'label' => 'Carro', //o nome da label
							'value' => array( // os valores que o select terá
								'Volvo',
								'Mercedes',
								'Audi'
							)
						),
						array(
							'type' => 'radio', //indico que o type é radio
							'name' => $custom_post_type.'_gender', //indico que o name recebe o valor de 'modelo_gender'
							'required' => true, // é obrigatório o preenchimento 
							'label' => 'Gênero', //o nome da label
							'value' => array( // os valores que o radio terá
								'Masculino',
								'Feminino',
								'Outros'
							)
						),
						array(
							'type' => 'textarea', //indico que o type é a tag textarea
							'name' => $custom_post_type.'_description', //indico que o name recebe o valor de 'modelo_description'
							'required' => true, // é obrigatório o preenchimento 
							'label' => 'Descrição', //o nome da label
							'placeholder' => 'Fale sobre você' // texto do placeholder
						),
						array(
							'type' => 'file', //indico que o type é file
							'name' => $custom_post_type.'_file', //indico que o name recebe o valor de 'modelo_file'
							'required' => true, // é obrigatório o preenchimento 
							'label' => 'Arquivo', //o nome da label
							'accept' => '*'	// indico que ele aceita tudo	
						),
						array(
							'type' => 'file', //indico que o type é file
							'name' => $custom_post_type.'_image', //indico que o name recebe o valor de 'modelo_image'
							'required' => true, // é obrigatório o preenchimento
							'label' => 'Imagem', //o nome da label
							'accept' => 'image/*', // indico que ele aceita somente imagem	
							'image' => true
						),
						array(
							'type' => 'file', //indico que o type é file
							'name' => $custom_post_type.'_audio', //indico que o name recebe o valor de 'modelo_audio'
							'required' => true, // é obrigatório o preenchimento 
							'label' => 'Audio', //o nome da label
							'accept' => '.pdf' // indico que ele aceita somente pdf	
						),
						array(
							'type' => 'text', //indico que o type é text
							'name' => $custom_post_type.'_age', //indico que o name recebe o valor de 'modelo_age'
							'required' => true, // é obrigatório o preenchimento 
							'label' => 'Idade', //o nome da label
							'placeholder' => 'Informe sua idade' //texto do placeholder
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
					'permission' => array(
						'admin'
					),
					'view' => true,
					'edit' => true,
					'delete' => true
				),
				'pages' => array(
					array(
						'title' => $nome['singular'],
						'slug' => $nome['slug'],
						'content' => '['.$nome['slug'].'_view]'
					),
					array(
						'title' => 'Editar '.$nome['singular'],
						'slug' => 'editar-'.$nome['slug'],
						'content' => '['.$nome['slug'].'_edit]'
					),
					array(
						'title' => 'Criar '.$nome['singular'],
						'slug' => 'criar-'.$nome['slug'],
						'content' => '['.$nome['slug'].'_create]'
					),
					array(
						'title' => $nome['plural'],
						'slug' => $nome['slug-plural'],
						'content' => '['.$nome['slug'].'_list]'
					)
				)
			)
		)
	);

	$i = 0;
	for($i = 0; $i < count($options_list); ++$i){
		if(get_option($options_list[$i]['option'])==''){ // função que recupera o valor
			add_option( // adiciona um valor 
				$options_list[$i]['option'],
				$options_list[$i]['value']
			);
		}
	}
	//ATUALIZAR 
	update_option(
		$options_list[0]['option'],
		$options_list[0]['value']
	);
}