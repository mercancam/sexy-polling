<?php 
global $wpdb;

if($id != 0) {
	//get the rows
	$sql = "SELECT * FROM ".$wpdb->prefix."sexy_forms WHERE id = '".$id."'";
	$row = $wpdb->get_row($sql);
}

//get template sarray
$sql = "SELECT name, id FROM ".$wpdb->prefix."wpsxp_sexy_templates";
$templates = $wpdb->get_results($sql);
?>
<form action="admin.php?page=sexypollingtemplates&act=wpsxp_submit_data&holder=templates" method="post" id="wpsxp_form">
<div style="overflow: hidden;margin: 0 0 10px 0;">
	<div style="float:right;">
		<button  id="wpsxp_form_save" class="button-primary">Save</button>
		<button id="wpsxp_form_save_close" class="button">Save & Close</button>
		<button id="wpsxp_form_save_new" class="button">Save & New</button>
		<a href="admin.php?page=sexypollingtemplates" id="wpsxp_add" class="button"><?php echo $t = $id == 0 ? 'Cancel' : 'Close';?></a>
	</div>
</div>
<table class="wpsxp_table">
	<tr>
		<td style="width: 180px;"><label for="wpsxp_name" title="New template name">Name <span style="color: red">*</span></label></td>
		<td><input name="name" id="wpsxp_name" type="text" value="<?php echo $wpsxp_name;?>" class="required" /></td>	
	</tr>
	<tr>
		<td><label for="wpsxp_id_template" title="Load default values from this template">Start values</label><br /><a href="http://2glux.com/projects/sexypolling/demo" target="_blank">See Templates Demo</a></td>
		<td>
			<select id="wpsxp_id_template" name="id_template">
				<?php 
					foreach($templates as $template) {
						echo '<option value="'.$template->id.'">'.$template->name.'</option>';
					}
				?>
			</select>
		</td>	
	</tr>
	<tr>
		<td><label title="Status">Status</label></td>
		<td>
			<label for="wpsxp_status_yes">Published</label>
			<input id="wpsxp_status_yes" type="radio" name="published" value="1" checked="checked"  />
			&nbsp;&nbsp;&nbsp;
			<label for="wpsxp_status_no">Unpublished</label>
			<input id="wpsxp_status_no" type="radio" name="published" value="0" />
		</td>	
	</tr>
</table>
<input type="hidden" name="task" value="" id="wpsxp_task">
<input type="hidden" name="id" value="0" >
</form>