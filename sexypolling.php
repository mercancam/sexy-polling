<?php
/*
Plugin Name: Sexy Polling
Plugin URI: http://2glux.com/projects/sexypolling
Description: Find out what your audience thinks!. See <a href="http://2glux.com/projects/sexypolling/demo">Sexy Polling Demo</a>. 
Author: 2GLux.com
Author URI: http://2glux.com
Version: 0.9.1
*/
session_start();
$plugin_version = '0.9.1';
$wpsxp_db_version = '0.9.1';
$wpsxp_options = get_option('wpsxp_settings');

define('wpsxp_PLUGINS_URL', plugins_url());
define('wpsxp_FOLDER', basename(dirname(__FILE__)));
define('wpsxp_SITE_URL', get_option('siteurl'));

/******************************
* includes
******************************/

if(isset($_GET['act']) && $_GET['act'] == 'wpsxp_submit_data') {
	if(isset($_GET['holder']) && $_GET['holder'] == 'polls')
		include('includes/admin/poll_submit.php');
	elseif(isset($_GET['holder']) && $_GET['holder'] == 'answers')
		include('includes/admin/answer_submit.php');
	elseif(isset($_GET['holder']) && $_GET['holder'] == 'categories')
		include('includes/admin/category_submit.php');
	elseif(isset($_GET['holder']) && $_GET['holder'] == 'templates')
		include('includes/admin/template_submit.php');
	elseif(isset($_GET['holder']) && $_GET['holder'] == 'sexyajax')
		include('includes/admin/sexyajax.php');
	elseif(isset($_GET['holder']) && $_GET['holder'] == 'generate_css')
		include('includes/generate.css.php');
	elseif(isset($_GET['holder']) && $_GET['holder'] == 'generate_js')
		include('includes/generate.js.php');
	exit();
}
include('includes/display-functions.php'); // display content functions
include('includes/sexypolling_widget.php'); // widget
include('includes/admin-page.php'); // the plugin options page HTML and save functions

function wpsxp_on_install() {
	include('includes/install/install.sql.php'); // install
}

register_activation_hook(__FILE__, 'wpsxp_on_install');

function wpsxp_on_uninstall() {
	include('includes/install/uninstall.sql.php'); // uninstall
}

register_uninstall_hook(__FILE__, 'wpsxp_on_uninstall');

add_action('wp_ajax_wpsxp_make_vote', 'wpsxp_make_vote');
add_action('wp_ajax_nopriv_wpsxp_make_vote', 'wpsxp_make_vote');

function wpsxp_make_vote() {
	include('includes/vote.php');
}

add_action('wp_ajax_wpsxp_make_addanswer', 'wpsxp_make_addanswer');
add_action('wp_ajax_nopriv_wpsxp_make_addanswer', 'wpsxp_make_addanswer');

function wpsxp_make_addanswer() {
	include('includes/addanswer.php');
}

?>