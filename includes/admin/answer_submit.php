<?php 
global $wpdb;
$id = (int) $_POST['id'];
$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : '';

$sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."wpsxp_sexy_answers";
$count_answers = $wpdb->get_var($sql);

if($id == 0 && $count_answers < 5) {
	$sql = "SELECT MAX(`ordering`) FROM `".$wpdb->prefix."wpsxp_sexy_answers` WHERE `id_poll` = ". (int) $_POST['id_poll'];
	$max_order = $wpdb->get_var($sql) + 1;
	
	$wpdb->query( $wpdb->prepare(
			"
			INSERT INTO ".$wpdb->prefix."wpsxp_sexy_answers
			( 
				`name`, `id_poll`, `published`, `ordering`
			)
			VALUES ( %s, %d, %d, %d)
			",
			$_POST['name'], $_POST['id_poll'], $_POST['published'], $max_order
	) );
	
	$insrtid = (int) $wpdb->insert_id;
	if($insrtid != 0) {
		if($task == 'save')
			$redirect = "admin.php?page=sexyanswers&act=edit&id=".$insrtid;
		elseif($task == 'save_new')
			$redirect = "admin.php?page=sexyanswers&act=new";
		else
			$redirect = "admin.php?page=sexyanswers";
	}
	else
		$redirect = "admin.php?page=sexyanswers&error=1";
}
else {
	$res = (int) $_REQUEST['reset_votes'];
	if($res == 1) {
		$sql = "DELETE FROM ".$wpdb->prefix."wpsxp_sexy_votes WHERE `id_answer` = '".$id."'";
		$wpdb->query($sql);
	}
	
	$wpscf_name = isset($_POST['name']) ? $_POST['name'] : '';
	$wpscf_id_poll = isset($_POST['id_poll']) ? $_POST['id_poll'] : 0;
	$wpscf_status = isset($_POST['published']) ? $_POST['published'] : 0;
	
	$q = $wpdb->query( $wpdb->prepare(
			"
			UPDATE ".$wpdb->prefix."wpsxp_sexy_answers
			SET
				`name` = %s, 
				`id_poll` = %d, 
				`published` = %d 
			WHERE
				`id` = '".$id."'
			",
			$wpscf_name, $wpscf_id_poll, $wpscf_status
	) );
	if($q !== false) {
		if($task == 'save')
			$redirect = "admin.php?page=sexyanswers&act=edit&id=".$id;
		elseif($task == 'save_new')
			$redirect = "admin.php?page=sexyanswers&act=new";
		else
			$redirect = "admin.php?page=sexyanswers";
	}
	else
		$redirect = "admin.php?page=sexyanswers&error=1";
}
header("Location: ".$redirect);
exit();
?>