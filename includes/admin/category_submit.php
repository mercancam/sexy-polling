<?php 
global $wpdb;
$id = (int) $_POST['id'];
$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : '';

if($id == 0) {
	$sql = "SELECT MAX(`ordering`) FROM `".$wpdb->prefix."wpsxp_sexy_categories` ";
	$max_order = $wpdb->get_var($sql) + 1;
	
	$wpdb->query( $wpdb->prepare(
			"
			INSERT INTO ".$wpdb->prefix."wpsxp_sexy_categories
			( 
				`name`, `published`, `ordering`
			)
			VALUES ( %s, %d, %d )
			",
			$_POST['name'], $_POST['published'], $max_order
	) );
	
	$insrtid = (int) $wpdb->insert_id;
	if($insrtid != 0) {
		if($task == 'save')
			$redirect = "admin.php?page=sexycategories&act=edit&id=".$insrtid;
		elseif($task == 'save_new')
			$redirect = "admin.php?page=sexycategories&act=new";
		else
			$redirect = "admin.php?page=sexycategories";
	}
	else
		$redirect = "admin.php?page=sexycategories&error=1";
}
else {
	
	$wpscf_name = isset($_POST['name']) ? $_POST['name'] : '';
	$wpscf_status = isset($_POST['published']) ? $_POST['published'] : 0;
	
	$q = $wpdb->query( $wpdb->prepare(
			"
			UPDATE ".$wpdb->prefix."wpsxp_sexy_categories
			SET
				`name` = %s, 
				`published` = %d 
			WHERE
				`id` = '".$id."'
			",
			$wpscf_name, $wpscf_status
	) );
	if($q !== false) {
		if($task == 'save')
			$redirect = "admin.php?page=sexycategories&act=edit&id=".$id;
		elseif($task == 'save_new')
			$redirect = "admin.php?page=sexycategories&act=new";
		else
			$redirect = "admin.php?page=sexycategories";
	}
	else
		$redirect = "admin.php?page=sexycategories&error=1";
}
header("Location: ".$redirect);
exit();
?>