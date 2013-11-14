<?php
global $wpdb;
error_reporting(0);
//header('Content-type: application/json');

$id = (int)$_POST['menu_id'];
$type = $_POST['type'];

if($type == 'reorder') {
	$table_name = str_replace(array('\'','"'), '', $_POST['table_name']);
	//get form configuration
	$order = str_replace('option_li_','',$_POST[order]);
	$order_array = explode(',',$order);
	$query ="UPDATE `".$table_name."` SET `ordering` = CASE `id`";
	foreach ($order_array as $key => $val)
	{
		$ord = $key+1;
		$query .= " WHEN ".$val." THEN '".$ord."'";
	}
	$query .= " END WHERE `id` IN (".$order.")";
	$wpdb->query($query);
}
elseif($type == 'reorder_list') {
	$table_name = str_replace(array('\'','"'), '', $_POST['table_name']);
	//get form configuration
	$order = str_replace('option_li_','',$_POST[order]);
	$order_array = explode(',',$order);
	foreach ($order_array as $key => $val)
	{
		$val_arr = explode('_',$val);
		$field_id = $val_arr[0];
		$form_id = $val_arr[1];
		$order_final_array[$form_id][] = $field_id;
	}

	foreach($order_final_array as $f_id => $ids_array) {
		$order = implode(',',$ids_array);
		$query ="UPDATE `".$table_name."` SET `ordering` = CASE `id`";
		foreach ($ids_array as $key => $val)
		{
			$ord = $key+1;
			$query .= " WHEN ".$val." THEN '".$ord."'";
		}
		$query .= " END WHERE `id` IN (".$order.")";
		$wpdb->query($query);
	}
}

exit();
?>