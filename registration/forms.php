<?php 

function registration_form($campos) { ?>
	<?php 
		$custom_post_type = 'registration';
		$option = get_option($custom_post_type.'_options');
		if ($campos['id']!=''){
			$user_id = $campos['id'];
			$username = get_userdata($user_id)->user_login;
			$registration_id = get_user_meta($user_id,$custom_post_type.'_id',true);
			foreach ($option['fields'] as $key => $field) {
				 if($field['type']=='text'||$field['type']=='textarea'){
				 	$campos[$field['name']] = get_post_meta($registration_id,$field['name'],true);
				 } elseif ($field['type']=='checkbox'||$field['type']=='radio'||$field['type']=='select') {
				 	$campos[$field['name']] = wp_get_post_terms($registration_id,$field['name']);
				 }elseif ($field['type']=='file'){ // se for do tipo 'arquivo'
					$field_value = wp_get_attachment_url(get_post_meta($registration_id,$field['name'],true));// função que retorna a url de um anexo
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
	<form id="<?php echo $custom_post_type.'_manage'; ?>" method="post" enctype="multipart/form-data">  
		<table>
			<tr class="form-unit">
				<td>
					<label for="<?php echo $custom_post_type; ?>_username">Login</label>
				</td>
				<td>
					<?php if ($campos['id']==''){ ?>
						<input type="text" name="<?php echo $custom_post_type; ?>_username" id="<?php echo $custom_post_type; ?>_username" value="" placeholder="Login" required="required">
					<?php } else { ?>
						<strong><?php echo $username; ?></strong>
						<input type="hidden" name="<?php echo $custom_post_type; ?>_username" id="<?php echo $custom_post_type; ?>_username" value="<?php echo $username; ?>" disabled="disabled">
					<?php } ?>
				</td>
				<td class="form-message"></td>
			</tr>
			<?php if ($campos['id']==''){ ?>
				<tr class="form-unit">
					<td>
						<label for="<?php echo $custom_post_type; ?>_password">Senha</label>
					</td>
					<td>
						<input type="password" name="<?php echo $custom_post_type; ?>_password" id="<?php echo $custom_post_type; ?>_password" value="" placeholder="Senha" required="required">
					</td>
					<td class="form-message"></td>
				</tr>
			<?php } else { ?>
				<tr class="form-unit">
					<td>
						<label for="<?php echo $custom_post_type; ?>_password">Mudar Senha</label>
					</td>
					<td>
						<input type="radio" name="change_password" id="change_password_yes" value="yes"><label for="change_password_yes">Sim</label>&nbsp;&nbsp;
						<input type="radio" name="change_password" id="change_password_no" value="no" checked="checked"><label for="change_password_no">Não</label><br> 
						<input type="password" name="<?php echo $custom_post_type; ?>_password" id="<?php echo $custom_post_type; ?>_password" value="" placeholder="Senha" required="required" style="display:none;">
					</td>
					<td class="form-message"></td>
				</tr>
			<?php } ?>
			<?php
				foreach ($option['fields'] as $key => $field) { ?>
					<?php if(($field['name']==$custom_post_type.'_email'&&$campos['id']=='')||($field['name']!=$custom_post_type.'_email')) { //email travado ?>
						<tr class="form-unit">
						<?php if($field['type']=='text'){ ?>
							<td>
								<label for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label>
							</td>
							<td>
								<input type="text" name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" placeholder="<?php echo $field['placeholder']; ?>" value="<?php echo $campos[$field['name']]; ?>"<?php if($field['required']){ echo ' required="required"'; } ?>>
							</td>
							<td class="form-message"></td>
						<?php } elseif($field['type']=='radio'){ ?>
							<td>
								<label><?php echo $field['label']; ?></label>
							</td>
							<td>
								<?php foreach ($field['value'] as $i => $radio) {  ?>
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
								<?php } ?>
							</td>
							<td class="form-message"></td>
						<?php } elseif($field['type']=='checkbox'){ ?>
							<td>
								<label><?php echo $field['label']; ?></label>
							</td>
							<td>
								<?php foreach ($field['value'] as $i => $checkbox) { ?>
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
								<?php } ?>
							</td>
							<td class="form-message"></td>
						<?php } elseif($field['type']=='select'){ ?>
							<td>
								<label><?php echo $field['label']; ?></label>
							</td>
							<td>
								<select name="<?php echo $field['name']; ?>"<?php if($field['required']){ echo ' required="required"'; } ?>>
									<option value="">Selecionar</option>
									<?php foreach ($field['value'] as $i => $select) { ?>
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
										<option value="<?php echo slugify($select); ?>"<?php echo $status; ?>><?php echo $select; ?></option>
									<?php } ?>
								</select>
							</td>
							<td class="form-message"></td>
						<?php } elseif($field['type']=='textarea'){ ?>
							<td>
								<label for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label>
							</td>
							<td>
								<textarea name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" placeholder="<?php echo $field['placeholder']; ?>"<?php if($field['required']){ echo ' required="required"'; } ?>><?php echo $campos[$field['name']]; ?></textarea>
							</td>
							<td class="form-message"></td>
						<?php } elseif($field['type']=='file'){ ?>
							<td>
								<label for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label>
							</td>
							<td>
								<?php echo $campos[$field['name']]; ?>
								<input type="file" name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" accept="<?php echo $field['accept']; ?>"<?php if($field['required']&&$campos['type']=='create'){ echo ' required="required"'; } ?>>
							</td>
							<td class="form-message"></td>
						<?php } ?>
						</tr>
					<?php } else { //email travado ?>
						<tr class="form-unit">
							<td>
								<label for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label>
							</td>
							<td>
								<strong><?php echo $campos[$field['name']]; ?></strong>
								<input type="hidden" name="<?php echo $field['name']; ?>" id="<?php echo $field['name']; ?>" placeholder="<?php echo $field['placeholder']; ?>" value="<?php echo $campos[$field['name']]; ?>"<?php if($field['required']){ echo ' required="required"'; } ?>>
							</td>
							<td class="form-message"></td>
						</tr>
					<?php } ?>
				<?php }
			?>
		</table>
		<input type="hidden" name="action" value="<?php echo $campos['type']; ?>">
		<?php if ($campos['id']!=''){ ?>
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
			<input type="hidden" name="registration_id" value="<?php echo $registration_id; ?>">
		<?php } ?>
		<input type="submit" value="Enviar">
	</form>
	<script type="text/javascript">
		<?php if ($campos['id']!=''){ ?> 
			$('#change_password_yes').change(function(){
				if($(this).is(':checked')){
					$('#registration_password').fadeIn();
				}
			});
			$('#change_password_no').change(function(){
				if($(this).is(':checked')){
					$('#registration_password').fadeOut();
				}
			});
		<?php } ?> 
		//VALIDATE ======
		$("#<?php echo $custom_post_type.'_manage'; ?>").validate({
			errorPlacement: function(error, element) {
				element.closest('.form-unit').find('.form-message').append(error);
			},
			rules: {
				<?php if ($campos['id']==''){ ?> 
					registration_username: {
						minlength: 3,
						maxlength: 25,
						nospace: true
					},
				<?php } ?>
				registration_email: {
					email: true
				},
				registration_phone: {
					minlength: 14
				},
				registration_cpf: {
					cpf: true
				},
				registration_cnpj: {
					cnpj: true
				},
				registration_file: {
					filesize: 300000   //max size 300kb
				}
			},
			messages: {
				registration_phone: {
					minlength: "Insira no mínimo 10 caracteres"
				},
				registration_file:{
					filesize: "O arquivo deve ser menor que 300KB."
				}
			}
		});
		//MASK ======
		$('#registration_phone').mask(phone_mask,spOptions);
		$('#registration_cpf').mask('000.000.000-00',{reverse: true});
		$('#registration_cnpj').mask('00.000.000/0000-00',{reverse: true});
	</script>
<?php }

function registration_admin_form() { ?>
	<?php 
		$custom_post_type = 'registration';
		$option = get_option($custom_post_type.'_admin');
	?>
	<style type="text/css">
		input[type=radio]+label,input[type=checkbox]+label{
			display: inline-block;
		}
	</style>
	<form id="<?php echo $custom_post_type.'_admin'; ?>" method="post" enctype="multipart/form-data">  
		<table>
			<?php
				foreach ($option['fields'] as $name => $field) { ?>
					<tr class="form-unit">
					<?php if($field['type']=='text'){ ?>
						<td>
							<label for="<?php echo $name; ?>"><?php echo $field['label']; ?></label>
						</td>
						<td>
							<input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $field['placeholder']; ?>" value="<?php echo $field['result']; ?>"<?php if($field['required']){ echo ' required="required"'; } ?>>
						</td>
						<td class="form-message"></td>
					<?php } if($field['type']=='color'){ ?>
						<td>
							<label for="<?php echo $name; ?>"><?php echo $field['label']; ?></label>
						</td>
						<td>
							<input type="color" name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $field['placeholder']; ?>" value="<?php echo $field['result']; ?>"<?php if($field['required']){ echo ' required="required"'; } ?>>
						</td>
						<td class="form-message"></td>
					<?php } elseif($field['type']=='radio'){ ?>
						<td>
							<label><?php echo $field['label']; ?></label>
						</td>
						<td>
							<?php foreach ($field['value'] as $i => $radio) {  ?>
								<?php 
									if(slugify($radio)==slugify($field['result'])){
										$status = ' checked="checked"';
									}
								?>
								<input type="radio" name="<?php echo $name; ?>" id="<?php echo $name.$i; ?>" value="<?php echo slugify($radio); ?>"<?php if($field['required']){ echo ' required="required"'; } ?><?php echo $status; ?>><label for="<?php echo $name.$i; ?>"><?php echo $radio; ?></label><br> 
							<?php } ?>
						</td>
						<td class="form-message"></td>
					<?php } elseif($field['type']=='checkbox'){ ?>
						<td>
							<label><?php echo $field['label']; ?></label>
						</td>
						<td>
							<?php foreach ($field['value'] as $i => $checkbox) { ?>
								<?php 
									$status = '';
									if($field['result']!=''&&$field['result']!=array()){
										foreach ($field['result'] as $j => $value) {
											if(slugify($value)==slugify($checkbox)){
												$status = ' checked="checked"';
											}
										} 
									}
								?>
								<input type="checkbox" name="<?php echo $name; ?>[]" id="<?php echo $name.$i; ?>" value="<?php echo slugify($checkbox); ?>"<?php if($field['required']){ echo ' required="required"'; } ?><?php echo $status; ?>><label for="<?php echo $name.$i; ?>"><?php echo $checkbox; ?></label><br>
							<?php } ?>
						</td>
						<td class="form-message"></td>
					<?php } elseif($field['type']=='select'){ ?>
						<td>
							<label><?php echo $field['label']; ?></label>
						</td>
						<td>
							<select name="<?php echo $name; ?>"<?php if($field['required']){ echo ' required="required"'; } ?>>
								<option value="">Selecionar</option>
								<?php foreach ($field['value'] as $i => $select) { ?>
									<?php 
										if(slugify($select)==slugify($field['result'])){
											$status = ' selected="selected"';
										}
									?>
									<option value="<?php echo slugify($select); ?>"<?php echo $status; ?>><?php echo $select; ?></option>
								<?php } ?>
							</select>
						</td>
						<td class="form-message"></td>
					<?php } elseif($field['type']=='textarea'){ ?>
						<td>
							<label for="<?php echo $name; ?>"><?php echo $field['label']; ?></label>
						</td>
						<td>
							<textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" placeholder="<?php echo $field['placeholder']; ?>"<?php if($field['required']){ echo ' required="required"'; } ?>><?php echo $field['result']; ?></textarea>
						</td>
						<td class="form-message"></td>
					<?php } elseif($field['type']=='file'){ ?>
						<td>
							<label for="<?php echo $name; ?>"><?php echo $field['label']; ?></label>
						</td>
						<td>
							<?php if($field['result']!=''){ echo '<img src="'.wp_get_attachment_url($field['result']).'">'; } ?>
							<input type="file" name="<?php echo $name; ?>" id="<?php echo $name; ?>" accept="<?php echo $field['accept']; ?>"<?php if($field['result']==''&&$field['required']==true){ echo ' required="required"'; } ?>>
						</td>
						<td class="form-message"></td>
					<?php } ?>
					</tr>
				<?php }
			?>
		</table>
		<input type="hidden" name="action" value="<?php echo $custom_post_type; ?>_admin_edit">
		<input type="submit" value="Enviar">
	</form>
	<script type="text/javascript">
		//VALIDATE ======
		$("#<?php echo $custom_post_type.'_admin'; ?>").validate({
			errorPlacement: function(error, element) {
				element.closest('.form-unit').find('.form-message').append(error);
			},
			rules: {
				registration_email: {
					email: true
				}
			}
		});
	</script>
<?php }