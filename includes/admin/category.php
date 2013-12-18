<?php 
global $wpdb;

if($id != 0) {
//get the rows
$sql = "SELECT * FROM ".$wpdb->prefix."wpsxp_sexy_categories WHERE id = '".$id."'";
$row = $wpdb->get_row($sql);
}

//set variables
$wpsxp_name = $id == 0 || $row->name == '' ? '' : $row->name;
$wpsxp_status = $id == 0 || $row->published == '' ? '1' : $row->published;

?>
<form action="admin.php?page=sexypolling&act=wpsxp_submit_data&holder=categories" method="post" id="wpsxp_form">
<div style="overflow: hidden;margin: 0 0 10px 0;">
	<div style="float:right;">
		<button  id="wpsxp_form_save" class="button-primary">Save</button>
		<button id="wpsxp_form_save_close" class="button">Save & Close</button>
		<button id="wpsxp_form_save_new" class="button">Save & New</button>
		<a href="admin.php?page=sexyfields"  class="button"><?php echo $t = $id == 0 ? 'Cancel' : 'Close';?></a>
	</div>
</div>
<table class="wpsxp_table">
	<tr>
		<td style="width: 180px;"><label for="wpsxp_name" title="Contact Box Name">Name <span style="color: red">*</span></label></td>
		<td><input name="name" id="wpsxp_name" type="text" value="<?php echo $wpsxp_name;?>" class="required" /></td>	
	</tr>
	
	<tr>
		<td><label title="Status">Status</label></td>
		<td>
			<label for="wpsxp_status_yes">Published</label>
			<input id="wpsxp_status_yes" type="radio" name="published" value="1" <?php if($wpsxp_status == 1) echo 'checked="checked"';?>  />
			&nbsp;&nbsp;&nbsp;
			<label for="wpsxp_status_no">Unpublished</label>
			<input id="wpsxp_status_no" type="radio" name="published" value="0"  <?php if($wpsxp_status == 0) echo 'checked="checked"';?>/>
		</td>	
	</tr>
	
	
	
</table>
<input type="hidden" name="task" value="" id="wpsxp_task">
<input type="hidden" name="id" value="<?php echo $id;?>" >
</form>
</div>