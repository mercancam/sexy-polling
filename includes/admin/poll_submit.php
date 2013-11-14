<?php 
global $wpdb;
$id = (int) $_POST['id'];
$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : '';

$sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."wpsxp_sexy_polls";
$count_polls = $wpdb->get_var($sql);

if($id == 0 && $count_polls < 1) {
	$sql = "SELECT MAX(`ordering`) FROM `".$wpdb->prefix."wpsxp_sexy_polls`";
	$max_order = $wpdb->get_var($sql) + 1;
	
	$wpdb->query( $wpdb->prepare(
			"
			INSERT INTO ".$wpdb->prefix."wpsxp_sexy_polls
			(
				`name`,`question`,`width`,`id_template`,`id_category`,`multiple_answers`,`number_answers`,`voting_period`,`voting_permission`,`answerpermission`,`autopublish`,`baranimationtype`,`coloranimationtype`,`reorderinganimationtype`,`dateformat`,`autoopentimeline`,`autoanimate`,`showresultbutton`,`date_start`,`date_end`,`published`, `ordering` 
			)
			VALUES ( %s, %s, %s, %d, %d, %s, %d, %s, %d, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d, %d )
			",
			$_POST['name'], $_POST['question'], $_POST['width'], $_POST['id_template'], $_POST['id_category'], $_POST['multiple_answers'], $_POST['number_answers'], $_POST['voting_period'], $_POST['voting_permission'], $_POST['answerpermission'], $_POST['autopublish'], $_POST['baranimationtype'], $_POST['coloranimationtype'], $_POST['reorderinganimationtype'], $_POST['dateformat'], $_POST['autoopentimeline'], $_POST['autoanimate'], $_POST['showresultbutton'], $_POST['date_start'], $_POST['date_end'], $_POST['published'], $max_order
	) );
	
	
	$insrtid = (int) $wpdb->insert_id;
	if($insrtid != 0) {
		if($task == 'save')
			$redirect = "admin.php?page=sexypolls&act=edit&id=".$insrtid;
		elseif($task == 'save_new')
			$redirect = "admin.php?page=sexypolls&act=new";
		else
			$redirect = "admin.php?page=sexypolls";
	}
	else
		$redirect = "admin.php?page=sexypolls&error=1";
}
else {
	$q = $wpdb->query( $wpdb->prepare(
			"
			UPDATE ".$wpdb->prefix."wpsxp_sexy_polls
			SET
				`name` = %s,
				`question` = %s,
				`width` = %s,
				`id_template` = %d,
				`id_category` = %d,
				`multiple_answers` = %s,
				`number_answers` = %d,
				`voting_period` = %s,
				`voting_permission` = %d,
				`answerpermission` = %d,
				`autopublish` = %s,
				`baranimationtype` = %s,
				`coloranimationtype` = %s,
				`reorderinganimationtype` = %s,
				`dateformat` = %s,
				`autoopentimeline` = %s,
				`autoanimate` = %s,
				`showresultbutton` = %s,
				`date_start` = %s,
				`date_end` = %s,
				`published` = %d
			WHERE
				`id` = '".$id."'
			",
			$_POST['name'], $_POST['question'], $_POST['width'], $_POST['id_template'], $_POST['id_category'], $_POST['multiple_answers'], $_POST['number_answers'], $_POST['voting_period'], $_POST['voting_permission'], $_POST['answerpermission'], $_POST['autopublish'], $_POST['baranimationtype'], $_POST['coloranimationtype'], $_POST['reorderinganimationtype'], $_POST['dateformat'], $_POST['autoopentimeline'], $_POST['autoanimate'], $_POST['showresultbutton'], $_POST['date_start'], $_POST['date_end'], $_POST['published']
	) );
	
	if($q !== false) {
		if($task == 'save')
			$redirect = "admin.php?page=sexypolls&act=edit&id=".$id;
		elseif($task == 'save_new')
			$redirect = "admin.php?page=sexypolls&act=new";
		else
			$redirect = "admin.php?page=sexypolls";
	}
	else
		$redirect = "admin.php?page=sexypolls&error=1";
}
header("Location: ".$redirect);
exit();
?>