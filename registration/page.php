<?php 
// https://clicknathan.com/web-design/automatically-create-pages-wordpress/
// https://wordpress.stackexchange.com/questions/191215/programmatically-set-page-template-based-on-page-id

/* ===== CREATE PAGES ===== */

add_action('init','create_pages_custom_registration');
function create_pages_custom_registration() {
	$custom_post_type = 'registration'; 
	$option = get_option($custom_post_type.'_options'); 
	$page_list = $option['pages'];
	$i = 0;
	for($i = 0; $i < count($page_list); ++$i){
		$page_check = get_page_by_title($page_list[$i]['title']);
		$page = array(
			'post_type' => 'page',
			'post_title' => $page_list[$i]['title'],
			'post_content' => $page_list[$i]['content'],
			'post_status' => 'publish',
			'post_author' => 1,
			'post_slug' => $page_list[$i]['slug']
		);
		if(!isset($page_check->ID) && !the_slug_exists($page_list[$i]['slug'])){
			$page_id = wp_insert_post($page);
		}
	}
}