<?php

function wpsxp_admin() {
	global $wpsxp_options;
	ob_start(); ?>
	<div class="wrap">
		<?php include ('admin/header.php');?>
		<?php include ('admin/content.php');?>
		<?php include ('admin/footer.php');?>
	</div>
	<?php
	echo ob_get_clean();
}

function wpsxp_add_options_link() {
	$icon_url=plugins_url( '/images/Box_tool_sexypolling_16.png' , __FILE__ );
	
	add_menu_page('Sexy Polling', 'Sexy Polling', 'manage_options', 'sexypolling', 'wpsxp_admin', $icon_url);
	
	$page1 = add_submenu_page('sexypolling', 'Sexy Polling Overview', 'Overview', 'manage_options', 'sexypolling', 'wpsxp_admin');
	$page2 = add_submenu_page('sexypolling', 'Polls', 'Polls', 'manage_options', 'sexypolls', 'wpsxp_admin');
	$page3 = add_submenu_page('sexypolling', 'Answers', 'Answers', 'manage_options', 'sexyanswers', 'wpsxp_admin');
	$page4 = add_submenu_page('sexypolling', 'Categories', 'Categories', 'manage_options', 'sexycategories', 'wpsxp_admin');
	$page5 = add_submenu_page('sexypolling', 'Templates', 'Templates', 'manage_options', 'sexypollingtemplates', 'wpsxp_admin');
	$page6 = add_submenu_page('sexypolling', 'Statistics', 'Statistics', 'manage_options', 'sexystatistics', 'wpsxp_admin');
	
	add_action('admin_print_scripts-' . $page1, 'wpsxp_load_overview_scripts');
	add_action('admin_print_scripts-' . $page2, 'wpsxp_load_polls_scripts');
	add_action('admin_print_scripts-' . $page3, 'wpsxp_load_answers_scripts');
	add_action('admin_print_scripts-' . $page4, 'wpsxp_load_categories_scripts');
	add_action('admin_print_scripts-' . $page5, 'wpsxp_load_template_scripts');
	add_action('admin_print_scripts-' . $page6, 'wpsxp_load_statistics_scripts');
}

function wpsxp_register_settings() {
	// creates our settings in the options table
	register_setting('wpsxp_settings_group', 'wpsxp_settings');
}

function wpsxp_load_overview_scripts() {
	wp_enqueue_style('wpsxp-styles10', plugin_dir_url( __FILE__ ) . 'css/admin.css');
}
function wpsxp_load_polls_scripts() {
	wp_enqueue_style('wpsxp-styles9', plugin_dir_url( __FILE__ ) . 'css/ui-lightness/jquery-ui-1.10.1.custom.css');
	wp_enqueue_style('wpsxp-styles10', plugin_dir_url( __FILE__ ) . 'css/admin.css');

	wp_enqueue_script('wpsxp-script14', plugin_dir_url( __FILE__ ) . 'js/admin.js', array('jquery','jquery-ui-core','jquery-ui-sortable'));
}
function wpsxp_load_categories_scripts() {
	wp_enqueue_style('wpsxp-styles9', plugin_dir_url( __FILE__ ) . 'css/ui-lightness/jquery-ui-1.10.1.custom.css');
	wp_enqueue_style('wpsxp-styles10', plugin_dir_url( __FILE__ ) . 'css/admin.css');

	wp_enqueue_script('wpsxp-script14', plugin_dir_url( __FILE__ ) . 'js/admin.js', array('jquery','jquery-ui-core','jquery-ui-sortable'));
}
function wpsxp_load_statistics_scripts() {
	wp_enqueue_style('wpsxp-styles9', plugin_dir_url( __FILE__ ) . 'css/ui-lightness/jquery-ui-1.10.1.custom.css');
	wp_enqueue_style('wpsxp-styles10', plugin_dir_url( __FILE__ ) . 'css/admin.css');

	wp_enqueue_script('wpsxp-script14', plugin_dir_url( __FILE__ ) . 'js/admin.js', array('jquery','jquery-ui-core','jquery-ui-sortable'));
	wp_enqueue_script('wpsxp-script15', plugin_dir_url( __FILE__ ) . 'js/highstock.js', array('jquery'));
	wp_enqueue_script('wpsxp-script16', plugin_dir_url( __FILE__ ) . 'js/exporting.js', array('jquery'));
}
function wpsxp_load_answers_scripts() {
	wp_enqueue_style('wpsxp-styles9', plugin_dir_url( __FILE__ ) . 'css/ui-lightness/jquery-ui-1.10.1.custom.css');
	wp_enqueue_style('wpsxp-styles10', plugin_dir_url( __FILE__ ) . 'css/admin.css');
	wp_enqueue_style('wpsxp-styles11', plugin_dir_url( __FILE__ ) . 'css/options_styles.css');

	wp_enqueue_script('wpsxp-script14', plugin_dir_url( __FILE__ ) . 'js/admin.js', array('jquery'));
	wp_enqueue_script('wpsxp-script15', plugin_dir_url( __FILE__ ) . 'js/options_functions.js',array('jquery','jquery-ui-core','jquery-ui-sortable'));
}
function wpsxp_load_template_scripts() {
	//wp_enqueue_style('wpsxp-styles1', plugin_dir_url( __FILE__ ) . 'css/ui-lightness/jquery-ui-1.10.1.custom.css');
	wp_enqueue_style('wpsxp-styles2', plugin_dir_url( __FILE__ ) . 'css/admin.css');
	wp_enqueue_style('wpsxp-styles3', plugin_dir_url( __FILE__ ) . 'css/colorpicker.css');
	wp_enqueue_style('wpsxp-styles4', plugin_dir_url( __FILE__ ) . 'css/layout.css');
	wp_enqueue_style('wpsxp-styles5', plugin_dir_url( __FILE__ ) . 'css/temp.css');
	//wp_enqueue_style('wpsxp-styles6', plugin_dir_url( __FILE__ ) . 'css/ui.slider.extras.css');
	wp_enqueue_style('wpsxp-styles7', plugin_dir_url( __FILE__ ) . 'css/main.css');

	wp_enqueue_script('wpsxp-script1', plugin_dir_url( __FILE__ ) . 'js/admin.js', array('jquery','jquery-ui-core','jquery-ui-sortable'));
	wp_enqueue_script('wpsxp-script2', plugin_dir_url( __FILE__ ) . 'js/colorpicker.js', array('jquery','jquery-ui-core'));
	wp_enqueue_script('wpsxp-script3', plugin_dir_url( __FILE__ ) . 'js/eye.js', array('jquery','jquery-ui-core'));
	wp_enqueue_script('wpsxp-script4', plugin_dir_url( __FILE__ ) . 'js/utils.js', array('jquery','jquery-ui-core'));
	//wp_enqueue_script('wpsxp-script5', plugin_dir_url( __FILE__ ) . 'js/sexypolling.js', array('jquery'));
}

add_action('admin_menu', 'wpsxp_add_options_link');
add_action('admin_init', 'wpsxp_register_settings');