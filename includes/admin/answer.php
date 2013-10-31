<?php 
global $wpdb;

if($id != 0) {
//get the rows
$sql = "SELECT * FROM ".$wpdb->prefix."wpsxp_sexy_answers WHERE id = '".$id."'";
$row = $wpdb->get_row($sql);
}

//get types
$types_sql = "SELECT `id`, `name` FROM `".$wpdb->prefix."sexy_field_types`";
$type_rows = $wpdb->get_results($types_sql);
//get polls
$polls_sql = "SELECT `id`, `name`, `id_template` FROM `".$wpdb->prefix."wpsxp_sexy_polls` order by `ordering`,`name` ";
$polls_rows = $wpdb->get_results($polls_sql);

//set variables
$wpsxp_name = $id == 0 || $row->name == '' ? '' : $row->name;
$wpsxp_id_poll = $id == 0 ? 0 : $row->id_poll;
$wpsxp_status = $id == 0 || $row->published == '' ? '1' : $row->published;

?>
<?php 
$sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."wpsxp_sexy_answers";
$count_fields = $wpdb->get_var($sql);
if($id == 0 && $count_fields >= 5) {
	?>
	<div style="color: rgb(235, 9, 9);font-size: 16px;font-weight: bold;">Please Upgrade to PRO Version to have more than 5 Sexy Answers!</div>
	<div id="cpanel" style="float: left;">
		<div class="icon" style="float: right;">
			<a href="http://2glux.com/projects/sexypolling" target="_blank" title="Buy PRO version">
				<table style="width: 100%;height: 100%;text-decoration: none;">
					<tr>
						<td align="center" valign="middle">
							<img src="<?php echo plugins_url( '../images/shopping_cart.png' , __FILE__ );?>" /><br />
							Buy Pro Version
						</td>
					</tr>
				</table>
			</a>
		</div>
	</div>
	<?php 
}
else {
?>
<form action="admin.php?page=sexypolling&act=wpsxp_submit_data&holder=answers" method="post" id="wpsxp_form">
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
		<td><label for="wpsxp_id_poll" title="Poll">Poll</label></td>
		<td>
			<select id="wpsxp_id_poll" name="id_poll">
				<?php 
					foreach($polls_rows as $poll) {
						$selected = $poll->id == $wpsxp_id_poll ? 'selected="selected"' : '';
						echo '<option value="'.$poll->id.'" '.$selected.'>'.$poll->name.'</option>';
					}
				?>
			</select>
		</td>	
	</tr>
	
	<tr>
		<td><label title="Reset Votes on this answer">Reset Votes</label></td>
		<td>
			<label for="wpsxp_reset_votes_yes">Yes</label>
			<input id="wpsxp_reset_votes_yes" type="radio" name="reset_votes" value="1" />
			&nbsp;&nbsp;&nbsp;
			<label for="wpsxp_reset_votes_no">No</label>
			<input id="wpsxp_reset_votes_no" type="radio" name="reset_votes" value="0" checked="checked" >
		</td>	
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
<?php }?>