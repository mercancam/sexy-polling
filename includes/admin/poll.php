<?php 
global $wpdb;

if($id != 0) {
//get the rows
$sql = "SELECT * FROM ".$wpdb->prefix."wpsxp_sexy_polls WHERE id = '".$id."'";
$row = $wpdb->get_row($sql);
}

//set variables
$wpsxp_name = $id == 0 ? '' : $row->name;
$wpsxp_question = $id == 0 ? '' : $row->question;
$wpsxp_poll_width = $id == 0 ? '98%' : $row->width;
$wpsxp_id_template = $id == 0 ? '1' : $row->id_template;
$wpsxp_id_category = $id == 0 ? '1' : $row->id_category;

$wpsxp_multiple_answers = $id == 0 ? '0' : $row->multiple_answers;
$wpsxp_number_answers = $id == 0 ? '0' : $row->number_answers;
$wpsxp_voting_period = $id == 0 ? '24' : $row->voting_period;
$wpsxp_voting_permission = $id == 0 ? '0' : $row->voting_permission;
$wpsxp_answerpermission = $id == 0 ? '0' : $row->answerpermission;
$wpsxp_autopublish = $id == 0 ? '1' : $row->autopublish;

$wpsxp_baranimationtype = $id == 0 ? 'linear' : $row->baranimationtype;
$wpsxp_coloranimationtype = $id == 0 ? 'linear' : $row->coloranimationtype;
$wpsxp_reorderinganimationtype = $id == 0 ? 'linear' : $row->reorderinganimationtype;

$wpsxp_dateformat = $id == 0 ? '1' : $row->dateformat;
$wpsxp_autoopentimeline = $id == 0 ? '1' : $row->autoopentimeline;
$wpsxp_autoanimate = $id == 0 ? '0' : $row->autoanimate;
$wpsxp_showresultbutton = $id == 0 ? '1' : $row->showresultbutton;

$wpsxp_date_start = $id == 0 ? '0000-00-00' : $row->date_start;
$wpsxp_date_end = $id == 0 ? '0000-00-00' : $row->date_end;

$wpsxp_status = $id == 0 ? '1' : $row->published;

$wpsxp_permissions = array
							(
								0 => array("id" => 0,"name" => "Public"),
								1 => array("id" => 1,"name" => "Registered"),
								2 => array("id" => 2,"name" => "None")
							);
$wpsxp_swing_options = array("linear","swing","easeInQuad","easeOutQuad","easeInOutQuad","easeInCubic","easeOutCubic","easeInOutCubic","easeInQuart","easeOutQuart","easeInOutQuart","easeInQuint","easeOutQuint","easeInOutQuint","easeInSine","easeOutSine","easeInOutSine","easeInExpo","easeOutExpo","easeInOutExpo","easeInCirc","easeOutCirc","easeInOutCirc","easeInElastic","easeOutElastic","easeInOutElastic","easeInBack","easeOutBack","easeInOutBack","easeInBounce","easeOutBounce","easeInOutBounce");

//get template sarray
$sql = "SELECT name, id FROM ".$wpdb->prefix."wpsxp_sexy_templates";
$wpsxp_templates = $wpdb->get_results($sql);
//get categories sarray
$sql = "SELECT name, id FROM ".$wpdb->prefix."wpsxp_sexy_categories";
$wpsxp_categories = $wpdb->get_results($sql);

$sql = "SELECT COUNT(id) FROM ".$wpdb->prefix."wpsxp_sexy_polls";
$wpsxp_count_polls = $wpdb->get_var($sql);
if($id == 0 && $wpsxp_count_polls >= 1) {
	?>
	<div style="color: rgb(235, 9, 9);font-size: 16px;font-weight: bold;">Please Upgrade to PRO Version to have more than 1 Sexy Polls!</div>
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
<form action="admin.php?page=sexypolls&act=wpsxp_submit_data&holder=polls" method="post" id="wpsxp_form">
<div style="overflow: hidden;margin: 0 0 10px 0;">
	<div style="float:right;">
		<button  id="wpsxp_form_save" class="button-primary">Save</button>
		<button id="wpsxp_form_save_close" class="button">Save & Close</button>
		<button id="wpsxp_form_save_new" class="button">Save & New</button>
		<a href="admin.php?page=sexypolls" id="wpsxp_add" class="button"><?php echo $t = $id == 0 ? 'Cancel' : 'Close';?></a>
	</div>
</div>
<table class="wpsxp_table">
	<tr>
		<td style="width: 180px;"><label for="wpsxp_name" title="Contact Box Name">Name <span style="color: red">*</span></label></td>
		<td><input name="name" id="wpsxp_name" type="text" value="<?php echo $wpsxp_name;?>" class="required" /></td>	
	</tr>
	<tr>
		<td><label for="wpsxp_question" title="Question">Question <span style="color: red">*</span></label></td>
		<td><input name="question" id="wpsxp_question" type="text" value="<?php echo $wpsxp_question;?>" class="required" /></td>	
	</tr>
	<tr>
		<td><label for="wpsxp_width" title="Poll width">Width</label></td>
		<td><input name="width" id="wpsxp_width" type="text" value="<?php echo $wpsxp_poll_width;?>" /></td>	
	</tr>
	<tr>
		<td><label for="wpsxp_id_template" title="Template">Template <span style="color: red">*</span></label><br /><a href="http://2glux.com/projects/sexypolling/demo" target="_blank">See Templates Demo</a></td>
		<td>
			<select id="wpsxp_id_template" name="id_template">
				<?php 
					foreach($wpsxp_templates as $template) {
						$selected = $template->id == $wpsxp_id_template ? 'selected="selected"' : '';
						echo '<option value="'.$template->id.'" '.$selected.'>'.$template->name.'</option>';
					}
				?>
			</select>
		</td>	
	</tr>
	<tr>
		<td><label for="wpsxp_id_category" title="Category">Category <span style="color: red">*</span></label></td>
		<td>
			<select id="wpsxp_id_category" name="id_category">
				<?php 
					foreach($wpsxp_categories as $category) {
						$selected = $category->id == $wpsxp_id_category ? 'selected="selected"' : '';
						echo '<option value="'.$category->id.'" '.$selected.'>'.$category->name.'</option>';
					}
				?>
			</select>
		</td>	
	</tr>
	
	<tr>
		<td style="height: 15px;" colspan="2"></td>
	</tr>
	<tr>
		<td><label title="Let voters to choose more that one answer when voting">Multiple Answers</label></td>
		<td>
			<label for="wpsxp_multiple_answers_yes">Yes</label>
			<input id="wpsxp_multiple_answers_yes" type="radio" name="multiple_answers" value="1" <?php if($wpsxp_multiple_answers == 1) echo 'checked="checked"';?>  />
			&nbsp;&nbsp;&nbsp;
			<label for="wpsxp_multiple_answers_no">No</label>
			<input id="wpsxp_multiple_answers_no" type="radio" name="multiple_answers" value="0"  <?php if($wpsxp_multiple_answers == 0) echo 'checked="checked"';?>/>
		</td>	
	</tr>
	<tr>
		<td><label for="wpsxp_number_answers" title="The maximum number of allowed checked options. Use ONLY when MULTIPLE ANSWERS set to YES. EXAMPLE: if set to 0, there is no limits on checked options count. if 3, users can check only 3 options.">Number Options</label></td>
		<td><input name="number_answers" id="wpsxp_number_answers" type="text" value="<?php echo $wpsxp_number_answers;?>" /></td>	
	</tr>
	<tr>
		<td><label for="wpsxp_voting_period" title="The period of time(claculated in hours) after which users can vote on this poll again. EXAMPLE: if 0, users can vote on this poll only once. If 0.5, they can vote every 30 minutes. If 1, they can vote once an hour. If 24 - once a day and so on...">Voting Period</label></td>
		<td><input name="voting_period" id="wpsxp_voting_period" type="text" value="<?php echo $wpsxp_voting_period;?>" /></td>	
	</tr>
	<tr>
		<td><label for="wpsxp_voting_permission" title="Who can vote on this poll">Voting Permission</label></td>
		<td>
			<select id="wpsxp_voting_permission" name="voting_permission">
				<?php 
					foreach($wpsxp_permissions as $permision) {
						$selected = $permision['id'] == $wpsxp_voting_permission ? 'selected="selected"' : '';
						echo '<option value="'.$permision["id"].'" '.$selected.'>'.$permision["name"].'</option>';
					}
				?>
			</select>
		</td>	
	</tr>
	
	<tr>
		<td style="height: 15px;" colspan="2"></td>
	</tr>
	<tr>
		<td><label for="wpsxp_answerpermission" title="Who can add an answer">Who can add an answer</label></td>
		<td>
			<select id="wpsxp_answerpermission" name="answerpermission">
				<?php 
					foreach($wpsxp_permissions as $permision) {
						$selected = $permision['id'] == $wpsxp_answerpermission ? 'selected="selected"' : '';
						echo '<option value="'.$permision["id"].'" '.$selected.'>'.$permision["name"].'</option>';
					}
				?>
			</select>
		</td>	
	</tr>
	<tr>
		<td><label title="New answers get published automatically"">Autopublishment</label></td>
		<td>
			<label for="wpsxp_autopublish_yes">Yes</label>
			<input id="wpsxp_autopublish_yes" type="radio" name="autopublish" value="1" <?php if($wpsxp_autopublish == 1) echo 'checked="checked"';?>  />
			&nbsp;&nbsp;&nbsp;
			<label for="wpsxp_autopublish_no">No</label>
			<input id="wpsxp_autopublish_no" type="radio" name="autopublish" value="0"  <?php if($wpsxp_autopublish == 0) echo 'checked="checked"';?>/>
		</td>	
	</tr>
	
	<tr>
		<td style="height: 15px;" colspan="2"></td>
	</tr>
	<tr>
		<td><label for="wpsxp_baranimationtype" title="Bar Animation Type">Bar Animation Type</label></td>
		<td>
			<select id="wpsxp_baranimationtype" name="baranimationtype">
				<?php 
					foreach($wpsxp_swing_options as $wpsxp_swing_option) {
						$selected = $wpsxp_swing_option == $wpsxp_baranimationtype ? 'selected="selected"' : '';
						echo '<option value="'.$wpsxp_swing_option.'" '.$selected.'>'.$wpsxp_swing_option.'</option>';
					}
				?>
			</select>
		</td>	
	</tr>
	<tr>
		<td><label for="wpsxp_coloranimationtype" title="Color Animation Type">Color Animation Type</label></td>
		<td>
			<select id="wpsxp_coloranimationtype" name="coloranimationtype">
				<?php 
					foreach($wpsxp_swing_options as $wpsxp_swing_option) {
						$selected = $wpsxp_swing_option == $wpsxp_coloranimationtype ? 'selected="selected"' : '';
						echo '<option value="'.$wpsxp_swing_option.'" '.$selected.'>'.$wpsxp_swing_option.'</option>';
					}
				?>
			</select>
		</td>	
	</tr>
	<tr>
		<td><label for="wpsxp_reorderinganimationtype" title="Reordering Animation Type">Reordering Animation Type</label></td>
		<td>
			<select id="wpsxp_reorderinganimationtype" name="reorderinganimationtype">
				<?php 
					foreach($wpsxp_swing_options as $wpsxp_swing_option) {
						$selected = $wpsxp_swing_option == $wpsxp_reorderinganimationtype ? 'selected="selected"' : '';
						echo '<option value="'.$wpsxp_swing_option.'" '.$selected.'>'.$wpsxp_swing_option.'</option>';
					}
				?>
			</select>
		</td>	
	</tr>
	
	<tr>
		<td style="height: 15px;" colspan="2"></td>
	</tr>
	<tr>
		<td><label title="Date Format">Date Format</label></td>
		<td>
			<label for="wpsxp_dateformat_yes">String</label>
			<input id="wpsxp_dateformat_yes" type="radio" name="dateformat" value="1" <?php if($wpsxp_dateformat == 1) echo 'checked="checked"';?>  />
			&nbsp;&nbsp;&nbsp;
			<label for="wpsxp_dateformat_no">Digits</label>
			<input id="wpsxp_dateformat_no" type="radio" name="dateformat" value="0"  <?php if($wpsxp_dateformat == 0) echo 'checked="checked"';?>/>
		</td>	
	</tr>
	<tr>
		<td><label title="Open timeline automatically">Open timeline automatically</label></td>
		<td>
			<label for="wpsxp_autoopentimeline_yes">Yes</label>
			<input id="wpsxp_autoopentimeline_yes" type="radio" name="autoopentimeline" value="1" <?php if($wpsxp_autoopentimeline == 1) echo 'checked="checked"';?>  />
			&nbsp;&nbsp;&nbsp;
			<label for="wpsxp_autoopentimeline_no">No</label>
			<input id="wpsxp_autoopentimeline_no" type="radio" name="autoopentimeline" value="0"  <?php if($wpsxp_autoopentimeline == 0) echo 'checked="checked"';?>/>
		</td>	
	</tr>
	<tr>
		<td><label title="Autoanimate for voted polls">Autoanimate for voted polls</label></td>
		<td>
			<label for="wpsxp_autoanimate_yes">Yes</label>
			<input id="wpsxp_autoanimate_yes" type="radio" name="autoanimate" value="1" <?php if($wpsxp_autoanimate == 1) echo 'checked="checked"';?>  />
			&nbsp;&nbsp;&nbsp;
			<label for="wpsxp_autoanimate_no">No</label>
			<input id="wpsxp_autoanimate_no" type="radio" name="autoanimate" value="0"  <?php if($wpsxp_autoanimate == 0) echo 'checked="checked"';?>/>
		</td>	
	</tr>
	<tr>
		<td><label title="Allow users to view the results even if they have not voted">Show View Button</label></td>
		<td>
			<label for="wpsxp_showresultbutton_yes">Yes</label>
			<input id="wpsxp_showresultbutton_yes" type="radio" name="showresultbutton" value="1" <?php if($wpsxp_showresultbutton == 1) echo 'checked="checked"';?>  />
			&nbsp;&nbsp;&nbsp;
			<label for="wpsxp_showresultbutton_no">No</label>
			<input id="wpsxp_showresultbutton_no" type="radio" name="showresultbutton" value="0"  <?php if($wpsxp_showresultbutton == 0) echo 'checked="checked"';?>/>
		</td>	
	</tr>
	
	<tr>
		<td style="height: 15px;" colspan="2"></td>
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
	<tr>
		<td><label for="wpsxp_date_start" title="Poll will start on (YYYY-mm-dd)">Date Start</label></td>
		<td><input name="date_start" id="wpsxp_date_start" type="text" value="<?php echo $wpsxp_date_start;?>" /></td>	
	</tr>
	<tr>
		<td><label for="wpsxp_date_end" title="Poll will end on (YYYY-mm-dd)">Date End</label></td>
		<td><input name="date_end" id="wpsxp_date_end" type="text" value="<?php echo $wpsxp_date_end;?>" /></td>	
	</tr>
</table>
<input type="hidden" name="task" value="" id="wpsxp_task">
<input type="hidden" name="id" value="<?php echo $id;?>" >
</form>
<?php }?>