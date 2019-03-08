<?php 

function manage_modelo(){
	if('POST'==$_SERVER['REQUEST_METHOD']&&!empty($_POST['action'])){// se o metodo de request for POST e não vazio
		$custom_post_type = 'modelo';
		$option = get_option($custom_post_type.'_options'); //recupera os valores de modelo_options
		$title = $_POST[$option['fields'][0]['name']];
		//principal
		if($_POST['action']=="create"){ // se caso a ação for criar
			$new_post = array( //parametros que devem ter
				'post_title'    => $title,
				'post_status'   => 'publish',          
				'post_type'     => $custom_post_type 
			);
			$post_id = wp_insert_post($new_post); //insere o post
		} elseif($_POST['action']=="edit"){ // se caso a ação for criar
			$update_post = array( //parametros que devem ter
				'ID'			=> (int)$_POST['id'],
				'post_title'    => $title
			);
			$post_id = wp_update_post($update_post); //insere o post
		}
		foreach ($option['fields'] as $key => $field) { //laço que percorre o array $option e indica a chave e o valor
			if($field['type']=='text'||$field['type']=='textarea'){ //se caso o tipo for igual a text ou textárea
				if($_POST[$field['name']]!=''){ // caso o valor passado pelo metodo post do array $field[] for diferente de vazio
					update_post_meta($post_id,$field['name'],$_POST[$field['name']]);// faz as atualizações dos campos indicados
				}
			} elseif($field['type']=='radio'||$field['type']=='select'){ // se o tipo for igual a radio ou a select
				if($_POST[$field['name']]!=''){ //se o valor de name recebido no método post for diferente  
					$category_id = term_exists((string)$_POST[$field['name']],$field['name'])['term_id']; //verifica se o termo existe e recupera os valores name,e o id_term
					wp_set_post_terms($post_id,$category_id,$field['name']); // função para definir os termos da postagem
				}
			} elseif($field['type']=='checkbox'){ //caso o tipo seja igual a checkbox
				if($_POST[$field['name']]!=''){ //se o valor recebido pelo metodo post for diferente de zero
					$category_id_container = array();
					foreach ($_POST[$field['name']] as $i => $value) { //laço que percorre que percorre o valor de name enviado 
						$category_id = term_exists((string)$value,$field['name'])['term_id']; //verifica se o termo existe e recupera os valores name,e o id_term
						array_push($category_id_container, $category_id);//adiciona mais elementos no final do array
					}
					wp_set_post_terms($post_id,$category_id_container,$field['name']); // define os termos da postagem
				}
			} elseif($field['type']=='file'){// se o input for do tipo "file"
				if($_FILES) { //variável reservada do php para arquivos
					if(!function_exists('wp_generate_attachment_metadata')){ //Função para salvar os arquivos upados nos diretórios abaixo
						require_once(ABSPATH . "wp-admin" . '/includes/image.php');
						require_once(ABSPATH . "wp-admin" . '/includes/file.php');
						require_once(ABSPATH . "wp-admin" . '/includes/media.php');
					}
					if($_FILES[$field['name']]['size'] == 0) { //caso nome e tamanho sejam 0
						//echo '<p align="center">Campo de envio foi enviado sem arquivo</p>';
					} elseif ($_FILES[$field['name']]['error'] !== UPLOAD_ERR_OK) { //caso ocorra algum erro na hora de upar os arquivos 
						//return "upload error : " . $_FILES[$field['name']]['error'];
						//echo '<p align="center">Algumas imagens não puderam ser enviadas</p>';
					} else {
						$attach_id = media_handle_upload($field['name'],$new_post); // salva o upload e cria um anexo para ele
						update_post_meta($post_id,$field['name'],$attach_id); //atualiza os campos indicados
					}
				}
			}
		}
		if($_POST['action']=="create"){
			echo '<p align="center">Suas informações foram enviadas com sucesso!</p>';
		} elseif($_POST['action']=="edit"){
			echo '<p align="center">Suas informações foram editadas com sucesso!</p>';
		}
	}
}

function delete_modelo(){
	if('POST'==$_SERVER['REQUEST_METHOD']&&!empty($_POST['action'])&&!empty($_POST['id'])&&$_POST['action']=='delete'){
		wp_trash_post($_POST['id']);
		echo '<p align="center">Publicação deletada com sucesso!</p>';
	}
}