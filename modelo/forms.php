<?php 

function modelo_form($campos) { ?>
	<?php
		// Aqui chamo uma função externa
		manage_modelo(); 
	?>
	<?php 
		$custom_post_type = 'modelo'; 
		$option = get_option($custom_post_type.'_options'); //pego os valores de modelo_options
		if ($campos['id']!=''){
			$modelo_id = $campos['id'];
			foreach ($option['fields'] as $key => $field) {
				 if($field['type']=='text'||$field['type']=='textarea'){
				 	$campos[$field['name']] = get_post_meta($modelo_id,$field['name'],true);
				 } elseif ($field['type']=='checkbox'||$field['type']=='radio'||$field['type']=='select') {
				 	$campos[$field['name']] = wp_get_post_terms($modelo_id,$field['name']);
				 }elseif ($field['type']=='file'){ // se for do tipo 'arquivo'
					$field_value = wp_get_attachment_url(get_post_meta($modelo_id,$field['name'],true));// função que retorna a url de um anexo
					if($field_value!=''){ // se for diferente de vazio
						if ($field['image']){ // se for imagem
							$campos[$field['name']] = '<img src="'.$field_value.'">'; // mostra o nome e a imagem
						} else {
							$campos[$field['name']] = '<a href="'.$field_value.'" target="_blank">Abrir</a>'; // mostra o nome e o link da imagem
						}
					} else {
						$campos[$field['name']] = 'Nenhum arquivo enviado'; //caso não encontre o arquivo
					}
				}
			}
		}
	?>
	<style type="text/css">
		input[type=radio]+label,input[type=checkbox]+label{
			display: inline-block;
		}
	</style>
	<!-- formulário que tem o id modelo_manager e o método post e aceita uploads de arquivos -->
	<form id="<?php echo $custom_post_type.'_manage'; ?>" method="post" enctype="multipart/form-data">  
		<table>
			<?php
				foreach ($option['fields'] as $key => $field) { //laço que percorre o array indicando a chave e o valor respectivamente ?>
					<tr class="form-unit">
					<?php if($field['type']=='text'){ //somente quando o campo for do tipo "text" ?>
						<td>
							<label for="<?php echo $field['name']; ?>"><?php echo $field['label']; // o atributo for recebe o valor do array $field['name'] e depois chamo o nome da label correspondente ?></label>
						</td>
						<td>
							<input type="text" name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" placeholder="<?php echo $field['placeholder']; ?>" value="<?php echo $campos[$field['name']]; ?>"<?php if($field['required']){ echo ' required="required"'; } ?>> <!-- o atributo name e o id recebem o valor do array $field['name'] e o placeholder e required também recebem seus valores correspondentes-->
						</td>
						<td class="form-message"></td>
					<?php } elseif($field['type']=='radio'){ //somente quando o campo for do tipo "radio" ?>
						<td>
							<label><?php echo $field['label']; ?></label> <!--label recebe o nome correspondente-->
						</td>
						<td>
							<?php foreach ($field['value'] as $i => $radio) {  //laço que percorre o array indicando a chave e o valor respectivamente para saber os valores do radio ?>
								<?php 
									$status = '';
									if($campos[$field['name']]!=''&&$campos[$field['name']]!=array()){
										foreach ($campos[$field['name']] as $j => $value) {
											if($value->slug==slugify($radio)){
												$status = ' checked="checked"';
											}
										} 
									}
								?>
								<input type="radio" name="<?php echo $field['name']; ?>" id="<?php echo $field['name'].$i; ?>" value="<?php echo slugify($radio); ?>"<?php if($field['required']){ echo ' required="required"'; } ?><?php echo $status; ?>><label for="<?php echo $field['name'].$i; ?>"><?php echo $radio; ?></label><br> 
							<?php } // o atributo name e o id recebem o valor do array $field['name'], o value recebe a função slugify(para url) e required e for que recebe o array $field['name'] concatenado com a chave $i para distinguir os ids também recebem seus valores correspondentes ?>
						</td>
						<td class="form-message"></td>
					<?php } elseif($field['type']=='checkbox'){ //somente quando o campo for do tipo "checkbox"?>
						<td>
							<label><?php echo $field['label']; ?></label><!--recebe o valor do array $field['label']-->
						</td>
						<td>
							<?php foreach ($field['value'] as $i => $checkbox) { //laço que percorre o array indicando a chave e o valor respectivamente para saber os valores do checkbox?>
								<?php 
									$status = '';
									if($campos[$field['name']]!=''&&$campos[$field['name']]!=array()){
										foreach ($campos[$field['name']] as $j => $value) {
											if($value->slug==slugify($checkbox)){
												$status = ' checked="checked"';
											}
										} 
									}
								?>
								<input type="checkbox" name="<?php echo $field['name']; ?>[]" id="<?php echo $field['name'].$i; ?>" value="<?php echo slugify($checkbox); ?>"<?php if($field['required']){ echo ' required="required"'; } ?><?php echo $status; ?>><label for="<?php echo $field['name'].$i; ?>"><?php echo $checkbox; ?></label><br>
							<?php } // o atributo name e o id recebem o valor do array $field['name'], o value recebe a função slugify e required e for que recebe o array $field['name'] concatenado com a chave $i para distinguir os ids também recebem seus valores correspondentes?>
						</td>
						<td class="form-message"></td>
					<?php } elseif($field['type']=='select'){ //somente quando o campo for do tipo "select"?>
						<td>
							<label><?php echo $field['label']; ?></label><!--recebe o valor do array $field['label']-->
						</td>
						<td>
							<select name="<?php echo $field['name']; ?>"<?php if($field['required']){ echo ' required="required"'; } //recebo os valores de $field['name'] em name e se caso o campo for obrigatório recebo $field['required']?>>
								<option value="">Selecionar</option>
								<?php foreach ($field['value'] as $i => $select) {//foreach chamando todos os option correspondente ao select?>
									<?php 
										$status = '';
										if($campos[$field['name']]!=''&&$campos[$field['name']]!=array()){
											foreach ($campos[$field['name']] as $j => $value) {
												if($value->slug==slugify($select)){
													$status = ' selected="selected"';
												}
											} 
										}
									?>
									<option value="<?php echo slugify($select); ?>"<?php echo $status; ?>><?php echo $select; ?></option><!--recebe os valores do select e imprime na tela-->
								<?php } ?>
							</select>
						</td>
						<td class="form-message"></td>
					<?php } elseif($field['type']=='textarea'){ //somente quando o campo for do tipo "textarea"?>
						<td>
							<label for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label><!-- o atributo for recebe o valor do array $field['name'] e $field['label'] imprime o nome do label-->
						</td>
						<td>
							<textarea name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" placeholder="<?php echo $field['placeholder']; ?>"<?php if($field['required']){ echo ' required="required"'; } ?>><?php echo $campos[$field['name']]; ?></textarea><!-- a tag textarea recebe os valores do name, do id, do placeholder e required correspondentes no array $field[]-->
						</td>
						<td class="form-message"></td>
					<?php } elseif($field['type']=='file'){ //somente quando o campo for do tipo "textarea"?>
						<td>
							<label for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label> <!-- o atributo for recebe o valor do array $field['name'] e $field['label'] imprime o nome do label-->
						</td>
						<td>
							<?php echo $campos[$field['name']]; ?>
							<input type="file" name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" accept="<?php echo $field['accept']; ?>"<?php if($field['required']&&$campos['type']=='create'){ echo ' required="required"'; } ?>><!-- recebe os valores do name, do id, o tipo de arquivo que pode mandar,do placeholder e required correspondentes no array $field[]-->
						</td>
						<td class="form-message"></td>
					<?php } ?>
					</tr>
				<?php }
			?>
		</table>
		<input type="hidden" name="action" value="<?php echo $campos['type']; ?>">
		<?php if ($campos['id']!=''){ ?>
			<input type="hidden" name="id" value="<?php echo $campos['id']; ?>">
		<?php } ?>
		<input type="submit" value="Enviar">
	</form>
	<script type="text/javascript">
		//VALIDATE ======
		$("#<?php echo $custom_post_type.'_manage'; ?>").validate({
			errorPlacement: function(error, element) {
				element.closest('.form-unit').find('.form-message').append(error);
			},
			rules: {
				modelo_email: {
					email: true
				},
				modelo_phone: {
					minlength: 14
				},
				modelo_cpf: {
					cpf: true
				},
				modelo_cnpj: {
					cnpj: true
				},
				modelo_file: {
					filesize: 300000   //max size 300kb
				}
			},
			messages: {
				modelo_phone: {
					minlength: "Insira no mínimo 10 caracteres"
				},
				modelo_file:{
					filesize: "O arquivo deve ser menor que 300KB."
				}
			}
		});
		//MASK ======
		$('#modelo_phone').mask(phone_mask,spOptions);
		$('#modelo_cpf').mask('000.000.000-00',{reverse: true});
		$('#modelo_cnpj').mask('00.000.000/0000-00',{reverse: true});
	</script>
<?php }