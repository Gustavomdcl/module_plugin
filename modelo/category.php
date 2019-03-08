<?php 
//função do wordpress com dois parametros, que tem o hook 'init' que determina o início do WordPress e o outro chama a função especificada
add_action( 'init', 'taxonomies_modelo' );
// Função para criar taxonomias correspondentes aos campos do formulário
function taxonomies_modelo() {
	$custom_post_type = 'modelo';
	$option = get_option($custom_post_type.'_options'); //recupera os valores de modelo_options
	foreach ($option['fields'] as $key => $field) { //laço que percorre o array indicando a chave e o valor respectivamente
		if($field['type']=='radio'||$field['type']=='checkbox'||$field['type']=='select'){ //se caso for do tipo radio ou checkbox ou select
			register_taxonomy( //função que adiciona ou sobrescreve uma taxonomia
				$field['name'],
				$custom_post_type,
				array(
					'label' => $field['label'],
					// 'rewrite' => array( 'slug' => $field['name'] ),
					'hierarchical' => true // terá uma hierarquia
				)
			);
		}
	}
}
add_action('admin_init','categories_modelo');
//Função para criar as categorias
function categories_modelo(){
	$custom_post_type = 'modelo';
	$option = get_option($custom_post_type.'_options');//recupera os valores de modelo_options
	foreach ($option['fields'] as $key => $field) { //laço que percorre o array indicando a chave e o valor respectivamente
		if($field['type']=='radio'||$field['type']=='checkbox'||$field['type']=='select'){ //se caso for do tipo radio ou checkbox ou select
			foreach ($field['value'] as $i => $value) { //laço que percorre o array indicando a chave e o valor respectivamente para recuperar os valores do campo 
				wp_insert_category(array( //para criar ou atualizar as categorias
					'cat_name' => $value, // define o nome da categoria
					'category_nicename' => slugify($value),//nome da categoria no display
					'taxonomy' => $field['name'] // as taxonomias recebem os valores de $field['name']
				));
			}
		}
	}	
}