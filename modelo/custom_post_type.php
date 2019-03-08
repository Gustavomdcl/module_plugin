<?php

add_action( 'init', 'create_post_type_modelo' );
function create_post_type_modelo() {
	//variável que recebe um array para distinguir o genero e se a palavra está no plural ou singular
	$nome = array( 
		'singular'				=> 'Modelo', // o valor singular recebe 'Modelo'
		'plural'				=> 'Modelos', // o valor plural recebe 'Modelos'
		'genero'				=> 'o', // o valor genero recebe 'o'
		'slug'					=> 'modelo' // o valor slug recebe 'modelo'
	);
	// Um if para distinguir o genero (o) ou (a) 
	if ($nome['genero'] == 'o') { // se o array nome com o valor genero for igual a 'o' então o valor none recebe nenhum
		$nome['none'] = 'Nenhum';
	}elseif ($nome['genero'] == 'a') {// se o array nome com o valor genero for igual a 'a' então o valor none recebe nenhuma
		$nome['none'] = 'Nenhuma';
	}
	// variável que recebe um array especificando em cada campo que queremos no menu modulo no painel do wordpress
	$labels = array(
		'name' 					=> $nome['plural'], // nome dos posts
		'singular_name' 		=> $nome['singular'], // nome do post
		'add_new' 				=> 'Adicionar '.$nome['singular'], // adiciona novo texto
		'add_new_item' 			=> 'Criar '.$nome['singular'], // criar novo post
		'edit_item' 			=> 'Editar '.$nome['singular'], //edição da página
		'new_item' 				=> 'Nov'.$nome['genero'].' '.$nome['singular'], //cria novo texto
		'all_items' 			=> 'Tod'.$nome['genero'].'s '.$nome['genero'].'s '.$nome['plural'], // todos os modelos
		'view_item' 			=> 'Ver '.$nome['singular'], //visualiza o post
		'search_items' 			=> 'Buscar '.$nome['singular'], // busca o post
		'not_found' 			=> $nome['none'].' '.$nome['singular'].' Encontrad'.$nome['genero'], // se não for encontrado aparece essa mensagem 
		'not_found_in_trash' 	=> $nome['none'].' '.$nome['singular'].' na Lixeira', // excluir um campo criado
		'parent_item_colon' 	=> '',
		'menu_name' 			=>  $nome['singular'] // nome do menu
	);
	// variável que determinar o que cada campo recebe, no caso a variável labels e também algumas condições que especifica o que iremos usar no modelo
	$args = array(
		'labels' 				=> $labels, // recebe todos os dados da variável acima
		'public' 				=> true, //se vai ser publicado (booleano)
		'publicly_queryable' 	=> true, // se vai ser vísivel para os leitores(booleano)
		'show_ui' 				=> true, // se vai ter uma interface (booleano)
		'show_in_menu' 			=> true, // se vai ter menu (booleano)
		'menu_icon' 			=> 'dashicons-screenoptions', // icone do modulo
		'rewrite' 				=> array( // se vai poder ser reescrito
									'slug' => $nome['slug'], 
									'with_front' => false,
								),
		'capability_type' 		=> 'post', // define o nivel que o usuário precisa ter para editar o post 
		'has_archive' 			=> true, // define se será criado um arquivo listando todas as entradas
		'hierarchical' 			=> false, // se vai ter uma hierarquia (booleano)
		'menu_position' 		=> null, // posição do link no menu 
		'supports' 				=> array('title','custom-fields','revisions','author') // o que será suportado pelo post
	  );
	// Função para criar ou editar um post, onde passamos o parametro que define o nome e algumas especificações do post que foram criadas na variável acima
    register_post_type( $nome['slug'], $args );
    //flush_rewrite_rules();
}