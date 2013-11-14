<?php 
global $wpdb;
delete_option('wpsxp_settings');

require_once(ABSPATH . '/wp-admin/includes/upgrade.php');

$sql = "DROP TABLE IF EXISTS `".$wpdb->prefix."wpsxp_sexy_polls`";
$wpdb->query($sql);

$sql = "DROP TABLE IF EXISTS `".$wpdb->prefix."wpsxp_sexy_answers`";
$wpdb->query($sql);

$sql = "DROP TABLE IF EXISTS `".$wpdb->prefix."wpsxp_sexy_votes`";
$wpdb->query($sql);

$sql = "DROP TABLE IF EXISTS `".$wpdb->prefix."wpsxp_sexy_categories`";
$wpdb->query($sql);

$sql = "DROP TABLE IF EXISTS `".$wpdb->prefix."wpsxp_sexy_templates`";
$wpdb->query($sql);
?>