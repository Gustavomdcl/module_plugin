<?php 

add_shortcode('modelo_view','modelo_view_shortcode');
function modelo_view_shortcode($shortcode_args, $shortcode_content){
	ob_start(); //inicio o "buffer" para salvar os dados que serão enviados 
		$custom_post_type = 'modelo';
		$option = get_option($custom_post_type.'_options');//recupera os valores de modelo_options
		$modelo_id = $_GET['id']; // pega o ID
		echo '<table>';
			echo '<tr><th>ID</th><td>'.$modelo_id.'</td></tr>';
			foreach ($option['fields'] as $key => $field) { //laço que percorre o array indicando a chave e o valor respectivamente
				if($field['type']=='text'||$field['type']=='textarea'){ // se o tipo for igual text ou o tipo for igual a textarea
					$field_value = get_post_meta($modelo_id,$field['name'],true);// pega os dados do post
					echo '<tr><th>'.$field['label'].'</th><td>'.$field_value.'</td></tr>';// imprime os dados do $field[] e do $field_value
				} elseif ($field['type']=='checkbox'||$field['type']=='radio'||$field['type']=='select') {// se os tipos forem iguais a checkbox ou a radio ou a select
					$field_value = ''; //variável que recebe um valor vazio 
					$field_value_final = wp_get_post_terms($modelo_id,$field['name']); //variável recebe a função do wordpress que recupera os termos de uma postagem
					$i = 0;
					for($i = 0; $i < count($field_value_final); ++$i){ // laço que enquanto a variável for menor que o número de termos da postagem irá acrescentar mais um
						if($i!=0){ //se a variável for diferente de zero, acrecento uma virgula e um espaço
							$field_value.=', ';
						}
						$field_value .= $field_value_final[$i]->name;
					}
					echo '<tr><th>'.$field['label'].'</th><td>'.$field_value.'</td></tr>'; // imprime os dados do $field[] e do $field_value
				} elseif ($field['type']=='file'){ // se for do tipo 'arquivo'
					$field_value = wp_get_attachment_url(get_post_meta($modelo_id,$field['name'],true));// função que retorna a url de um anexo
					if($field_value!=''){ // se for diferente de vazio
						if ($field['image']){ // se for imagem
							echo '<tr><th>'.$field['label'].'</th><td><img src="'.$field_value.'"></td></tr>'; // mostra o nome e a imagem
						} else {
							echo '<tr><th>'.$field['label'].'</th><td><a href="'.$field_value.'" target="_blank">Abrir</a></td></tr>'; // mostra o nome e o link da imagem
						}
					} else {
						echo '<tr><th>'.$field['label'].'</th><td>Nenhum arquivo enviado</td></tr>'; //caso não encontre o arquivo
					}
				}
			}
		echo '</table>';
		echo '<br>';
	return ob_get_clean(); //exclui o buffer de saída atual
}