<?php 
// Chama uma função dentro do post onde recebe dois argumentos, um com o nome do shortcode e outro com a função que será executada
add_shortcode('registration_list','registration_list_shortcode');
// Função para filtrar o conteudo do post
function registration_list_shortcode($shortcode_args, $shortcode_content) {
	ob_start(); //inicio o "buffer" para salvar os dados que serão enviados 
	//Pages =====
		$backup = $post;
		$backup_paged = $paged;
		global $post, $paged;
	//Pages =====  
		$custom_post_type = 'registration';
		$option = get_option($custom_post_type.'_options');//recupera os valores de registration_options
		$url = get_permalink();

	?>
	<?php if ($_GET['view']!='') { ?>
		<?php 
			registration_view(array(
				'custom_post_type' => $custom_post_type,
				'option' => $option,
				'id' => $_GET['view']
			)); 
		?>
	<?php } else { ?>
		<style type="text/css">
			li{
				list-style: none;
			}
			input[type=radio]+label,input[type=checkbox]+label{
				display: inline-block;
			}
			table tr td {
				vertical-align: top;
			}
			a{
				cursor:pointer;
			}
			.container_popup {
				width: 100%;
				height: 100%;
				background-color: #000000eb;
				position: fixed;
				top: 0px;
				left: 0px;
				display:none;
			}
			.content_popup {
				display: table-row;
			}
			.popup {
				display: table-cell;
				vertical-align: middle;
				text-align: center;
			}
    		.popup>div {
    			display: inline-block;
    			max-width: 300px;
    			background: #fff; 
    			padding: 20px;
    			box-sizing: border-box;
    		}
		</style>
		<form method="get" action="<?php echo $url; ?>">
			<table><tr>
				<?php foreach ($option['fields'] as $key => $field) { //laço que percorre o array indicando a chave e o valor respectivamente?>
					<?php if($field['type']=='radio'||$field['type']=='checkbox'||$field['type']=='select'){ // se o tipo for radio ou checkbox ou select?>
						<td>
							<strong><?php echo $field['label']; ?>:</strong><br><br> <!-- recebe o valor de label -->
							<?php foreach ($field['value'] as $i => $value) { //laço que percorre o array indicando a chave e o valor respectivamente para saber os valores do checkbox?> 
								<input type="checkbox" name="<?php echo $field['name']; ?>[]" id="<?php echo $field['name'].$i; ?>" value="<?php echo slugify($value); ?>"><label for="<?php echo $field['name'].$i; ?>"><?php echo $value; ?></label><br><!--o atributo name,o id, value e o label recebem respectivamente os valores de $field[]-->
							<?php } ?>
						</td>
					<?php } ?>
				<?php } ?>
			</tr></table>
			<input type="submit" value="Buscar">
		</form>
		<?php 
			$filters = array(
				'relation' => 'AND', // relação com a busca no banco de dados
			);
			//FILTRO
				if($_GET!=array()){ // pega os dados inseridos 
					foreach ($_GET as $field => $value) { //laço que percorre o array indicando a chave e o valores do $_GET
						if($value[0]!=''){ //caso o primeiro valor seja difente de vazio
							foreach ($value as $i => $value_interno) { //laço que percorre o array indicando a chave e dando o valor de $value_interno
								array_push( // adiciona um ou mais elementos no final de um array
									$filters,
										array(
										'taxonomy' => $field,
										'field'    => 'slug',
										'terms'    => $value_interno,
									)
								);
							}
						} else {
							array_push( // adiciona um ou mais elementos no final de um array
								$filters, // filtros selecionados
									array(
									'taxonomy' => $field,// taxonomia recebe valor de $field
									'field'    => 'slug', // o campor recebe um termo de taxonomia
									'terms'    => $value, //termo de taxonomia
									
								)
							);
						}
					}
				}
			//FILTRO
			$args = array( // array com parametros da pagina
				//Pages =====
					'posts_per_page' => 2, //quantidade de posts por página
					'paged' => $paged, // número de páginas
				//Pages =====
					'post_type' => $custom_post_type, // tipo de post
					'tax_query' => $filters, //oega os parametros de taxonomia
			);
			$the_query = new WP_Query($args); // uma função do wordpress que faz a solicitação de postagens
			//Pages =====
				query_posts($args); //faz a consulta principal de uma página
			//Pages =====
			//if ( $the_query->have_posts() ) { 
			//Pages =====
				if(have_posts()){ //função do wordpress que verica se há posts
			//Pages =====
				echo '<table>';
					echo '<tr>';
						echo '<th>ID</th>';
						foreach ($option['fields'] as $key => $field) {
							foreach ($option['list']['fields'] as $i => $value) {
								if($field['name']==$value){
									echo '<th>';
									echo $field['label'];
									echo '</th>';
								}
							}
						}
						if ($option['list']['view']) {
							echo '<th>Ver</th>';
						}
					echo '</tr>';
					//while ( $the_query->have_posts() ) {
						//$the_query->the_post();
					//Pages =====
						while(have_posts()){ the_post(); // função do wordpress que retorna o próximo post
					//Pages =====
						$registration_id = get_the_ID();
						echo '<tr>';
							echo '<td>'.$registration_id.'</td>';
							foreach ($option['fields'] as $key => $field) { //laço que percorre o array indicando a chave e o valor respectivamente
								foreach ($option['list']['fields'] as $i => $value) {
									if($field['name']==$value){
										if($field['type']=='text'||$field['type']=='textarea'){ // se o tipo for igual text ou o tipo for igual a textarea
											$field_value = get_post_meta($registration_id,$field['name'],true);// pega os dados do post
											echo '<td>'.$field_value.'</td>';// imprime os dados do $field[] e do $field_value
										} elseif ($field['type']=='checkbox'||$field['type']=='radio'||$field['type']=='select') {// se os tipos forem iguais a checkbox ou a radio ou a select
											$field_value = ''; //variável que recebe um valor vazio 
											$field_value_final = wp_get_post_terms($registration_id,$field['name']); //variável recebe a função do wordpress que recupera os termos de uma postagem
											$i = 0;
											for($i = 0; $i < count($field_value_final); ++$i){ // laço que enquanto a variável for menor que o número de termos da postagem irá acrescentar mais um
												if($i!=0){ //se a variável for diferente de zero, acrecento uma virgula e um espaço
													$field_value.=', ';
												}
												$field_value .= $field_value_final[$i]->name;
											}
											echo '<td>'.$field_value.'</td>'; // imprime os dados do $field[] e do $field_value
										} elseif ($field['type']=='file'){ // se for do tipo 'arquivo'
											$field_value = wp_get_attachment_url(get_post_meta($registration_id,$field['name'],true));// função que retorna a url de um anexo
											if($field_value!=''){ // se for diferente de vazio
												if ($field['image']){ // se for imagem
													echo '<td><img src="'.$field_value.'"></td>'; // mostra o nome e a imagem
												} else {
													echo '<td><a href="'.$field_value.'" target="_blank">Abrir</a></td>'; // mostra o nome e o link da imagem
												}
											} else {
												echo '<td>Nenhum arquivo enviado</td>'; //caso não encontre o arquivo
											}
										}
									}
								}
							}
							if ($option['list']['view']) {
								echo '<td><a href="'.$url.'?view='.$registration_id.'">Ver</a></td>';
							}
						echo '</tr>';
					}
				echo '</table>'; ?>
				<?php //Pages ===== ?>
					<nav class="pagination content">
						<?php 
							the_posts_pagination(array( //defini a paginação
								'prev_text' => 'Anterior',
								'next_text' => 'Próximo',
								'screen_reader_text' => '&nbsp;'
							));
						?>
					</nav>
				<?php //Pages =====
			}
			wp_reset_postdata(); //restaura o post após o loop
		?>
	<?php } ?>
 	<?php 
 	//Pages =====
		$post = $backup; //restore current object
		$paged = $backup_paged;
		wp_reset_query(); //zera o banco antes do loop iniciar
	//Pages =====
 	return ob_get_clean(); //exclui o buffer de saída atual
}