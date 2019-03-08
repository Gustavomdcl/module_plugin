<?php 

add_action('init','manage_registration');
function manage_registration(){
	$custom_post_type = 'registration'; 
	if('POST'==$_SERVER['REQUEST_METHOD']&&!empty($_POST['action'])&&($_POST['action']=='create_'.$custom_post_type||$_POST['action']=='edit_'.$custom_post_type)){
		if($_POST['action']=='create_'.$custom_post_type){ 
			$user_email_check = get_user_by('email',$_POST[$custom_post_type.'_email']);
			$user_login_check = get_user_by('login',$_POST[$custom_post_type.'_username']);
			if(empty($user_email_check) && empty($user_login_check)){
				/* ==== USER ==== */
					$userdata = array(
						'user_login'    =>   $_POST[$custom_post_type.'_username'],
						'user_pass'     =>   $_POST[$custom_post_type.'_password'],
						'user_email'    =>   $_POST[$custom_post_type.'_email'],
						'role'   		=>   'disabled',
					);
					$user = wp_insert_user($userdata);

					$activation = generateRandomString();
					add_user_meta($user,'activation',$activation);

				/* ==== CUSTOM POST TYPE ==== */
					$option = get_option($custom_post_type.'_options'); 
					$title = $_POST[$custom_post_type.'_username'];
					
					$new_post = array( 
						'post_title'    => $title,
						'post_status'   => 'publish',          
						'post_type'     => $custom_post_type 
					);
					$post_id = wp_insert_post($new_post); 
					foreach ($option['fields'] as $key => $field) { 
						if($field['type']=='text'||$field['type']=='textarea'){ 
							if($_POST[$field['name']]!=''){ 
								update_post_meta($post_id,$field['name'],$_POST[$field['name']]);
							}
						} elseif($field['type']=='radio'||$field['type']=='select'){ 
							if($_POST[$field['name']]!=''){ 
								$category_id = term_exists((string)$_POST[$field['name']],$field['name'])['term_id']; 
								wp_set_post_terms($post_id,$category_id,$field['name']); 
							}
						} elseif($field['type']=='checkbox'){ 
							if($_POST[$field['name']]!=''){ 
								$category_id_container = array();
								foreach ($_POST[$field['name']] as $i => $value) { 
									$category_id = term_exists((string)$value,$field['name'])['term_id']; 
									array_push($category_id_container, $category_id);
								}
								wp_set_post_terms($post_id,$category_id_container,$field['name']); 
							}
						} elseif($field['type']=='file'){
							if($_FILES) { 
								if(!function_exists('wp_generate_attachment_metadata')){ 
									require_once(ABSPATH . "wp-admin" . '/includes/image.php');
									require_once(ABSPATH . "wp-admin" . '/includes/file.php');
									require_once(ABSPATH . "wp-admin" . '/includes/media.php');
								}
								if($_FILES[$field['name']]['size'] == 0) { 
									
								} elseif ($_FILES[$field['name']]['error'] !== UPLOAD_ERR_OK) { 
									
									
								} else {
									$attach_id = media_handle_upload($field['name'],$new_post); 
									update_post_meta($post_id,$field['name'],$attach_id); 
								}
							}
						}
					}

				/* ==== RELATE ==== */
					add_user_meta($user,$custom_post_type.'_id',$post_id);
					update_post_meta($post_id,'user_id',$user);

				/* ==== EMAIL ==== */
					registration_success_email(array(
						'user_id' => $user
					));

					wp_redirect(get_site_url().'/cadastro?registration_result=true&registration_email='.$_POST[$custom_post_type.'_email']);
					exit;
			} else if (!empty($user_email_check)) {
				wp_redirect(get_site_url().'/cadastro?registration_result=false&registration_email='.$_POST[$custom_post_type.'_email']);
				exit;
			} else if (!empty($user_login_check)) {
				wp_redirect(get_site_url().'/cadastro?registration_result=taken&registration_login='.$_POST[$custom_post_type.'_username']);
				exit;
			}
		} else if($_POST['action']=='edit_'.$custom_post_type){
			/* ==== USER ==== */
				if($_POST['change_password']=='yes'&&$_POST[$custom_post_type.'_password']!=''){
					$userdata = array(
						'ID'    		=>   $_POST['user_id'],
						'user_pass'     =>   $_POST[$custom_post_type.'_password']
					);
					$user = wp_update_user($userdata);
				}

			/* ==== CUSTOM POST TYPE ==== */
				$option = get_option($custom_post_type.'_options'); 

				$post_id = $_POST['registration_id']; 
				foreach ($option['fields'] as $key => $field) { 
					if($field['type']=='text'||$field['type']=='textarea'){ 
						if($_POST[$field['name']]!=''){ 
							update_post_meta($post_id,$field['name'],$_POST[$field['name']]);
						}
					} elseif($field['type']=='radio'||$field['type']=='select'){ 
						if($_POST[$field['name']]!=''){ 
							$category_id = term_exists((string)$_POST[$field['name']],$field['name'])['term_id']; 
							wp_set_post_terms($post_id,$category_id,$field['name']); 
						}
					} elseif($field['type']=='checkbox'){ 
						if($_POST[$field['name']]!=''){ 
							$category_id_container = array();
							foreach ($_POST[$field['name']] as $i => $value) { 
								$category_id = term_exists((string)$value,$field['name'])['term_id']; 
								array_push($category_id_container, $category_id);
							}
							wp_set_post_terms($post_id,$category_id_container,$field['name']); 
						}
					} elseif($field['type']=='file'){
						if($_FILES) { 
							if(!function_exists('wp_generate_attachment_metadata')){ 
								require_once(ABSPATH . "wp-admin" . '/includes/image.php');
								require_once(ABSPATH . "wp-admin" . '/includes/file.php');
								require_once(ABSPATH . "wp-admin" . '/includes/media.php');
							}
							if($_FILES[$field['name']]['size'] == 0) { 
								
							} elseif ($_FILES[$field['name']]['error'] !== UPLOAD_ERR_OK) { 
								
								
							} else {
								$attach_id = media_handle_upload($field['name'],$new_post); 
								update_post_meta($post_id,$field['name'],$attach_id); 
							}
						}
					}
				}

			wp_redirect(get_site_url().'/editar-perfil');
			exit;
		}
	}
}

add_action('init','registration_admin_edit');
function registration_admin_edit(){
	$custom_post_type = 'registration'; 
	if('POST'==$_SERVER['REQUEST_METHOD']&&!empty($_POST['action'])&&$_POST['action']==$custom_post_type.'_admin_edit'){
		$option = get_option($custom_post_type.'_admin');
		foreach ($option['fields'] as $name => $field) { 
			if($field['type']=='text'||$field['type']=='color'||$field['type']=='textarea'){ 
				if($_POST[$name]!=''){ 
					$option['fields'][$name]['result'] = $_POST[$name];
				}
			} elseif($field['type']=='radio'||$field['type']=='select'){ 
				if($_POST[$name]!=''){ 
					$option['fields'][$name]['result'] = $_POST[$name]; 
				}
			} elseif($field['type']=='checkbox'){ 
				if($_POST[$name]!=''){ 
					$option['fields'][$name]['result'] = $_POST[$name];
				}
			} elseif($field['type']=='file'){
				if($_FILES) { 
					if(!function_exists('wp_generate_attachment_metadata')){ 
						require_once(ABSPATH . "wp-admin" . '/includes/image.php');
						require_once(ABSPATH . "wp-admin" . '/includes/file.php');
						require_once(ABSPATH . "wp-admin" . '/includes/media.php');
					}
					if($_FILES[$name]['size'] == 0) { 
						
					} elseif ($_FILES[$name]['error'] !== UPLOAD_ERR_OK) { 
						
						
					} else {
						$attach_id = media_handle_upload($name,$new_post); 
						$option['fields'][$name]['result'] = $attach_id;
					}
				}
			}
		}
		update_option(
			$custom_post_type.'_admin',
			$option
		);
	}
}