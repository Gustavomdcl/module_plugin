<?php 

//https://codex.wordpress.org/Adding_Administration_Menus
add_action( 'admin_menu', 'registration_admin_menu' );
function registration_admin_menu() {
	add_menu_page(
		'Administração de Registros',//Título
		'Registro Admin',//Menu
		'manage_options',//Capability https://codex.wordpress.org/Roles_and_Capabilities#Capabilities
		'registration_options',//Slug
		'registration_admin_page',//Função
		'dashicons-id'//Ícone
	);
}
function registration_admin_page() { ?>
	<div class="wrap">
		<?php registration_admin_form(); ?>
	</div><!-- .wrap -->
<?php }
