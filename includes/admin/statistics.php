<?php 
global $wpdb;

$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : '';
$ids = isset($_REQUEST['ids']) ?  $_REQUEST['ids'] : array();
$filter_state = isset($_REQUEST['filter_state']) ? (int) $_REQUEST['filter_state'] : 2;
$filter_search =  isset($_REQUEST['filter_search']) ? stripslashes(str_replace(array('\'','"'), '', trim($_REQUEST['filter_search']))) : '';

//get the rows
$sql = 
		"
			SELECT 
				sp.id,
				sp.name,
				sp.question,
				sp.published,
				MAX(sp.id) AS max_id,
				COUNT(sa.id) AS num_answers,
				st.name AS template_title,
				st.id AS template_id,
				sc.name AS category_title, 
				sc.id AS category_id
			FROM ".$wpdb->prefix."wpsxp_sexy_polls  sp
			LEFt JOIN ".$wpdb->prefix."wpsxp_sexy_answers AS sa ON sa.id_poll=sp.id
			LEFt JOIN ".$wpdb->prefix."wpsxp_sexy_categories AS sc ON sc.id=sp.id_category
			LEFT JOIN  ".$wpdb->prefix."wpsxp_sexy_templates AS st ON st.id=sp.id_template 
			WHERE 1 
		";
if($filter_state == 1)
	$sql .= " AND sp.published = '1'";
elseif($filter_state == 0)
	$sql .= " AND sp.published = '0'";
if($filter_search != '') {
	if (stripos($filter_search, 'id:') === 0) {
		$sql .= " AND sp.id = " . (int) substr($filter_search, 3);
	}
	else {
		$sql .= " AND (sp.name LIKE '%".$filter_search."%' OR sp.question LIKE '%".$filter_search."%') ";
	}
}
$sql .= " GROUP BY sp.id ORDER BY sp.`ordering`,`id` ASC";
$rows = $wpdb->get_results($sql);
?>
<form action="admin.php?page=sexystatistics" method="post" id="wpsxp_form">
<div style="overflow: hidden;margin: 0 0 10px 0;">
	<div style="float: left;">
		<select id="wpsxp_filter_state" class="wpsxp_select" name="filter_state">
			<option value="2" <?php if($filter_state == 2) echo 'selected="selected"';?> >Select Status</option>
			<option value="1"<?php if($filter_state == 1) echo 'selected="selected"';?> >Published</option>
			<option value="0"<?php if($filter_state == 0) echo 'selected="selected"';?> >Unpublished</option>
		</select>
		<input type="search" placeholder="Filter items by name" value="<?php echo $filter_search;?>" id="wpsxp_filter_search" name="filter_search">
		<button id="wpsxp_filter_search_submit" class="button-primary">Search</button>
		<a href="admin.php?page=sexystatistics"  class="button">Reset</a>
	</div>
</div>
<table class="widefat">
	<thead>
		<tr>
			<th nowrap align="left" style="text-align: left;padding-left: 22px;">Name</th>
			<th nowrap align="left" style="text-align: left;">Question</th>
			<th nowrap align="center" style="text-align: left;">Category</th>
			<th nowrap align="left" style="text-align: left;">Template</th>
			<th nowrap align="center" style="width: 50px;text-align: center;">Answers</th>
			<th nowrap align="center" style="width: 30px;text-align: center;">Id</th>
		</tr>
	</thead>
<tbody id="" table_name="<?php echo $wpdb->prefix;?>wpsxp_sexy_polls" reorder_type="">
<?php        
			$k = 0;
			for($i=0; $i < count( $rows ); $i++) {
				$row = $rows[$i];
?>
				<tr class="row<?php echo $k; ?> " id="option_li_<?php echo $row->id; ?>">
					<td valign="middle" align="left" style="vertical-align: middle;padding-left: 22px;">
						<a href="admin.php?page=sexystatistics&act=showstatistics&id=<?php echo $row->id;?>"><?php echo $row->name; ?></a>
					</td>
					<td valign="middle" align="left" style="vertical-align: middle;padding-left: 22px;">
						<a href="admin.php?page=sexystatistics&act=showstatistics&id=<?php echo $row->id;?>"><?php echo $row->question; ?></a>
					</td>
					<td valign="middle" align="left" style="vertical-align: middle;">
						<?php echo $row->category_title; ?>
					</td>
					<td valign="middle" align="left" style="vertical-align: middle;">
						<a href="admin.php?page=sexypollingtemplates&act=edit&id=<?php echo $row->template_id;?>"><?php echo $row->template_title; ?></a>
					</td>
					<td valign="top" align="center" style="vertical-align: middle;">
						<?php echo $row->num_answers; ?>
					</td>
					<td valign="middle" align="center" style="vertical-align: middle;">
						<?php echo $row->id; ?>
					</td>
				</tr>
<?php
				$k = 1 - $k;
			} // for
?>
</tbody>
</table>
<input type="hidden" name="task" value="" id="wpsxp_task" />
<input type="hidden" name="ids[]" value="" id="wpsxp_def_id" />
</form>