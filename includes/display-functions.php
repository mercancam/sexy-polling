<?php
global $wpdb;

function wpsxp_sexypoll_shortcode_function( $atts ) {
	extract( shortcode_atts( array(
		'id' => 0,
	), $atts ) );
	
	wpsxp_enqueue_front_scripts($id);
	return wpsxp_render_poll($id);
	
}
add_shortcode( 'sexypoll', 'wpsxp_sexypoll_shortcode_function' );

//add_action('template_redirect','wpsxp_my_shortcode_head');
function wpsxp_my_shortcode_head(){
	global $posts;
	global $wpsxp_token;
	$pattern = get_shortcode_regex();
	preg_match('/(\[(sexypoll) id="([0-9]+)"\])/s', $posts[0]->post_content, $matches);
	if (is_array($matches) && $matches[2] == 'sexypoll') {
		$poll_id = (int) $matches[3];
		wpsxp_enqueue_front_scripts($poll_id);
	}
}

function wpsxp_enqueue_front_scripts($poll_id) {
	global $wpdb;
	global $current_user;
	$wpsxp_userRegistered =  $current_user->ID == 0 ? 0 : 1;
	
	wp_enqueue_style('wpsxp-styles1', plugin_dir_url( __FILE__ ) . 'assets/css/main.css');
	wp_enqueue_style('wpsxp-styles2', plugin_dir_url( __FILE__ ) . 'assets/css/sexycss-ui.css');
	wp_enqueue_style('wpsxp-styles3', plugin_dir_url( __FILE__ ) . 'assets/css/countdown.css');
	wp_enqueue_style('wpsxp-styles4' . $poll_id, admin_url() . 'admin.php?page=sexypolling&act=wpsxp_submit_data&holder=generate_css&id_poll='.$poll_id.'&module_id=0');
	
	wp_enqueue_script('wpsxp-script1', plugin_dir_url( __FILE__ ) . 'assets/js/selectToUISlider.jQuery.js', array('jquery','jquery-ui-core','jquery-ui-slider'));
	wp_enqueue_script('wpsxp-script2', plugin_dir_url( __FILE__ ) . 'assets/js/color.js', array('jquery','jquery-ui-core'));
	wp_enqueue_script('wpsxp-script3', plugin_dir_url( __FILE__ ) . 'assets/js/countdown.js', array('jquery','jquery-ui-core'));
	wp_enqueue_script('wpsxp-script4' . $poll_id, admin_url() . 'admin.php?page=sexypolling&act=wpsxp_submit_data&holder=generate_js&id_poll='.$poll_id.'&wpsxp_userRegistered='.$wpsxp_userRegistered, array('jquery'));
	wp_enqueue_script('wpsxp-script5', plugin_dir_url( __FILE__ ) . 'assets/js/sexypolling.js', array('jquery','jquery-ui-core','jquery-effects-core'));
	
}

function wpsxp_render_poll($poll_id) {
	global $wpdb;
	global $current_user;
	$wpsxp_userRegistered =  $current_user->ID == 0 ? 0 : 1;
	$module_id = 0;
	
	//get ip
	$REMOTE_ADDR = null;
	if(isset($_SERVER['REMOTE_ADDR'])) {
		$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
	}
	elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
		$REMOTE_ADDR = $_SERVER['HTTP_CLIENT_IP'];
	}
	elseif(isset($_SERVER['HTTP_VIA'])) {
		$REMOTE_ADDR = $_SERVER['HTTP_VIA'];
	}
	else { $REMOTE_ADDR = 'Unknown';
	}
	$sexyip = $REMOTE_ADDR;
	
	ob_start();
	
	$polling_words = array('Votes','Total Votes','First Vote','Last Vote','Show Timeline','Hide Timeline','Vote','View','Please select answer','You have already voted on this poll','Add new answer','Add an answer...','Add','Your answer will appear after moderation','Scaling','Relative','Absolute','This poll will start on ','This poll expired on ','Back to Answers','Estimated time you can vote again ...','OK','Estimated time ...','Allowed count of checked options is','You do not have permissions to vote on this poll!','Powered by');
	
	//get polls
	$query = 'SELECT '.
					'sp.id polling_id, '.
					'sp.id_template id_template, '.
					'sp.date_start date_start, '.
					'sp.date_end date_end, '.
					'sp.multiple_answers multiple_answers, '.
					'sp.voting_period voting_period, '.
					'sp.number_answers number_answers, '.
					'sp.voting_permission voting_permission, '.
					'sp.answerpermission answerpermission, '.
					'sp.autopublish autopublish, '.
					'sp.baranimationtype baranimationtype, '.
					'sp.coloranimationtype coloranimationtype, '.
					'sp.reorderinganimationtype reorderinganimationtype, '.
					'sp.dateformat dateformat, '.
					'sp.autoopentimeline autoopentimeline, '.
					'sp.autoanimate autoanimate, '.
					'sp.showresultbutton showresultbutton, '.
					'sp.width poll_width, '.
					'st.styles styles, '.
					'sp.name polling_name, '.
					'sp.question polling_question, '.
					'sa.id answer_id, '.
					'sa.name answer_name '.
					'FROM '.
					'`'.$wpdb->prefix.'wpsxp_sexy_polls` sp '.
					'JOIN '.
					'`'.$wpdb->prefix.'wpsxp_sexy_answers` sa ON sa.id_poll = sp.id '.
					'AND sa.published = \'1\' '.
					'LEFT JOIN '.
					'`'.$wpdb->prefix.'wpsxp_sexy_templates` st ON st.id = sp.id_template '.
					'WHERE sp.published = \'1\' '.
					'AND sp.id = '.$poll_id.' '.
					'ORDER BY sp.ordering,sp.name,sa.ordering,sa.name';
	$pollings_array = $wpdb->get_results($query);
	if(sizeof($pollings_array) == 0) {
		$pollings = array();
	}
	else {
		for ($i=0, $n=count( $pollings_array ); $i < $n; $i++) {
			$pollings[$pollings_array[$i]->polling_id][] = $pollings_array[$i];
		}
	}
	
	$polling_select_id = array();
	$custom_styles = array();
	$voted_ids = array();
	$start_disabled_ids = array();
	$end_disabled_ids = array();
	$date_now = strtotime("now");
	$voting_periods = array();
	$voting_permissions = array();
	$number_answers_array = array();
	$answerPermission = array();
	$autoPublish = array();
	$autoOpenTimeline = array();
	$dateFormat = array();
	$autoAnimate = array();
	$sexyAnimationTypeBar = array();
	$sexyAnimationTypeContainer = array();
	$sexyAnimationTypeContainerMove = array();
	
	if(sizeof($pollings) > 0) {
		foreach ($pollings as $poll_index => $polling_array) {
	
			//create parameters array
			$autoPublish[$poll_index] = $polling_array[0]->autopublish;
			$autoOpenTimeline[$poll_index] = $polling_array[0]->autoopentimeline;
			$dateFormat[$poll_index] = $polling_array[0]->dateformat == 1 ? 'str' : 'digits';
			$autoAnimate[$poll_index] = $polling_array[0]->autoanimate;
			$sexyAnimationTypeBar[$poll_index] = $polling_array[0]->baranimationtype;
			$sexyAnimationTypeContainer[$poll_index] = $polling_array[0]->coloranimationtype;
			$sexyAnimationTypeContainerMove[$poll_index] = $polling_array[0]->reorderinganimationtype;
			$showresultbutton = $polling_array[0]->showresultbutton;
	
			$number_answers = $polling_array[0]->number_answers;
			$number_answers_array[$poll_index] = $number_answers;
			$voting_period = $polling_array[0]->voting_period;
			$voting_periods[$poll_index] = $voting_period;
	
			//check ACL to add answer
			$permission_to_show_add_answer_block = ($polling_array[0]->answerpermission == 0 || ($polling_array[0]->answerpermission == 1 && $wpsxp_userRegistered == 1)) ? true : false; 
	
			//check ACL to vote
			$permission_to_vote = ($polling_array[0]->voting_permission == 0 || ($polling_array[0]->voting_permission == 1 && $wpsxp_userRegistered == 1)) ? true : false;
			$voting_permissions[$poll_index] = $permission_to_vote;
	
			//check start,end dates
			if($polling_array[0]->date_start != '0000-00-00' && $polling_array[0]->date_start != 'NULL' && $polling_array[0]->date_start != '' &&  $date_now < strtotime($polling_array[0]->date_start)) {
				$datevoted = strtotime($polling_array[0]->date_start);
				$hours_diff = ($datevoted - $date_now) / 3600;
				$start_disabled_ids[] = array($poll_index,$polling_words[17] . date('F j, Y',strtotime($polling_array[0]->date_start)),$hours_diff);
			}
			if($polling_array[0]->date_end != '0000-00-00' && $polling_array[0]->date_end != 'NULL' && $polling_array[0]->date_end != '' &&  $date_now > strtotime($polling_array[0]->date_end)) {
				$end_disabled_ids[] = array($poll_index,$polling_words[18] . date('F j, Y',strtotime($polling_array[0]->date_end)));
			}
	
			//check ip
			$query = "SELECT sv.`ip`,sv.`date` FROM ".$wpdb->prefix."wpsxp_sexy_votes sv JOIN ".$wpdb->prefix."wpsxp_sexy_answers sa ON sa.id_poll = '$poll_index' WHERE sv.id_answer = sa.id AND sv.ip = '$sexyip' ORDER BY sv.`date` DESC LIMIT 1";
			$row = $wpdb->get_row($query);
			$num_rows = $wpdb->num_rows;
			if($num_rows > 0) {
				$datevoted = strtotime($row->date);
				$hours_diff = ($date_now - $datevoted) / 3600;
				if($voting_period == 0 && !in_array($poll_index,array_keys($voted_ids))) {
					$voted_ids[$poll_index] = '17520';//two years
				}
				elseif(!in_array($poll_index,array_keys($voted_ids)) && ($hours_diff < $voting_period))
				$voted_ids[$poll_index] = $voting_period - $hours_diff;
			}
	
			//check cookie
			if (isset($_COOKIE["sexy_poll_$poll_index"])) {
				$datevoted = $_COOKIE["sexy_poll_$poll_index"];
				$hours_diff = ($date_now - $datevoted) / 3600;
				if(!in_array($poll_index,array_keys($voted_ids)))
					$voted_ids[$poll_index] = $voting_period - $hours_diff;
			}
	
			$est_time = isset($voted_ids[$poll_index]) ? (float)$voted_ids[$poll_index] : -1;
	
			//set styles
			$custom_styles[$poll_index] = $polling_array[0]->styles;
			echo '<div class="polling_container_wrapper" id="mod_'.$module_id.'_'.$poll_index.'" roll="'.$module_id.'"><div class="polling_container" id="poll_'.$poll_index.'">';
			echo '<div class="polling_name">'.stripslashes($polling_array[0]->polling_question).'</div>';
	
			$multiple_answers = $polling_array[0]->multiple_answers;
			$multiple_answers_info_array[$poll_index] = $multiple_answers;
	
			$colors_array = array("black","blue","red","litegreen","yellow","liteblue","green","crimson","litecrimson");
			echo '<ul class="polling_ul">';
			foreach ($polling_array as $k => $poll_data) {
				$color_index = $k % 20 + 1;
				$data_color_index = $k % 9;
				echo '<li id="answer_'.$poll_data->answer_id.'" class="polling_li"><div class="animation_block"></div>';
				echo '<div class="answer_name"><label uniq_index="'.$module_id.'_'.$poll_data->answer_id.'" class="twoglux_label">'.stripslashes($poll_data->answer_name).'</label></div>';
				echo '<div class="answer_input">';
	
				if($multiple_answers == 0)
					echo '<input  id="'.$module_id.'_'.$poll_data->answer_id.'" type="radio" class="poll_answer '.$poll_data->answer_id.' twoglux_styled" value="'.$poll_data->answer_id.'" name="'.$poll_data->polling_id.'" data-color="'.$colors_array[$data_color_index].'" />';
				else
					echo '<input  id="'.$module_id.'_'.$poll_data->answer_id.'" type="checkbox" class="poll_answer '.$poll_data->answer_id.' twoglux_styled" value="'.$poll_data->answer_id.'" name="'.$poll_data->polling_id.'"  data-color="'.$colors_array[$data_color_index].'" />';
	
				echo '</div><div class="sexy_clear"></div>';
				echo '<div class="answer_result">
				<div class="answer_navigation polling_bar_'.$color_index.'" id="answer_navigation_'.$poll_data->answer_id.'"><div class="grad"></div></div>
				<div class="answer_votes_data" id="answer_votes_data_'.$poll_data->answer_id.'">'.$polling_words[0].': <span id="answer_votes_data_count_'.$poll_data->answer_id.'"></span><span id="answer_votes_data_count_val_'.$poll_data->answer_id.'" style="display:none"></span> (<span id="answer_votes_data_percent_'.$poll_data->answer_id.'">0</span><span style="display:none" id="answer_votes_data_percent_val_'.$poll_data->answer_id.'"></span>%)</div>
				<div class="sexy_clear"></div>
				</div>';
				echo '</li>';
			}
			echo '</ul>';
	
			//check perrmision, to show add answer option
			if($permission_to_show_add_answer_block) {
				echo '<div class="answer_wrapper opened" ><div style="padding:6px">';
				echo '<div class="add_answer"><input name="answer_name" class="add_ans_name" value="'.$polling_words[11].'" />
				<input type="button" value="'.$polling_words[12].'" class="add_ans_submit wpsxp_hidden" /><input type="hidden" value="'.$poll_index.'" class="poll_id" /><img class="loading_small" src="'.plugin_dir_url( __FILE__ ).'assets/images/loading_small.gif" /></div>';
				echo '</div></div>';
			}
	
			$new_answer_bar_index = ($k + 1) % 20 + 1;
	
			echo '<span class="polling_bottom_wrapper1"><img src="'.plugin_dir_url( __FILE__ ).'assets/images/loading_polling.gif" class="polling_loading" />';
			echo '<input type="button" value="'.$polling_words[6].'" class="polling_submit" id="poll_'.$module_id.'_'.$poll_index.'" />';
			$result_button_class = (($showresultbutton == 0) && ($est_time < 0)) ? 'hide_sexy_button' : '';
			echo '<input type="button" value="'.$polling_words[7].'" class="polling_result '.$result_button_class.'" id="res_'.$module_id.'_'.$poll_index.'" /></span>';
			echo '<div class="polling_info"><table cellpadding="0" cellspacing="0" border="0"><tr><td class="left_col">'.$polling_words[1].':<span class="total_votes_val" style="display:none"></span> </td><td class="total_votes right_col"></td></tr><tr><td class="left_col">'.$polling_words[2].': </td><td class="first_vote right_col"></td></tr><tr><td class="left_col">'.$polling_words[3].': </td><td class="last_vote right_col"></td></tr></table></div>';
	
			//timeline
			$polling_select_id[$poll_index]['select1'] = 'polling_select_'.$module_id.'_'.$poll_index.'_1';
			$polling_select_id[$poll_index]['select2'] = 'polling_select_'.$module_id.'_'.$poll_index.'_2';
	
			//get count of total votes, min and max dates of voting
			$query = "SELECT COUNT(sv.`id_answer`) total_count, MAX(sv.`date`) max_date,MIN(sv.`date`) min_date FROM `".$wpdb->prefix."wpsxp_sexy_votes` sv JOIN `".$wpdb->prefix."wpsxp_sexy_answers` sa ON sa.id_poll = '$poll_index' WHERE sv.id_answer = sa.id";
			$row_total = $wpdb->get_row($query, ARRAY_A);
			$count_total_votes = $row_total['total_count'];
			$min_date = strtotime($row_total['min_date']);
			$max_date = strtotime($row_total['max_date']);
			//if no votes, set time to current
			if((int)$min_date == 0) {
				$min_date = $max_date = strtotime("now");
			}
	
			$timeline_array = array();
	
			for($current = $min_date; $current <= $max_date; $current += 86400) {
				$timeline_array[] = $current;
			}
	
			//check, if max date is not included in timeline array, then add it.
			if(date('F j, Y', $max_date) !== date('F j, Y', $timeline_array[sizeof($timeline_array) - 1]))
				$timeline_array[] = $max_date;
	
			echo '<div class="timeline_wrapper">';
			echo '<div class="timeline_icon" title="'.$polling_words[4].'"></div>';
			echo '<div class="sexyback_icon" title="'.$polling_words[19].'"></div>';
			if($permission_to_show_add_answer_block) {
				if(!in_array($poll_index,$voted_ids)) {
					$add_ans_txt = $polling_words[10];
					$o_class = 'opened';
				}
				else {
					$add_ans_txt = $polling_words[9];
					$o_class = 'voted_button';
				}
				echo '<div class="add_answer_icon '.$o_class.'" title="'.$add_ans_txt.'"></div>';
			}
	
			echo '<div class="scale_icon" title="'.$polling_words[14].'"></div>';
	
			echo '<div class="timeline_select_wrapper" >';
			echo '<div style="padding:5px 6px"><select class="polling_select1" id="polling_select_'.$module_id.'_'.$poll_index.'_1" name="polling_select_'.$module_id.'_'.$poll_index.'_1">';
	
			$optionGroups = array();
			foreach ($timeline_array as $k => $curr_time) {
				if(!in_array(date('F Y', $curr_time),$optionGroups)) {
	
					if (sizeof($optionGroups) != 0)
						echo '</optgroup>';
	
					$optionGroups[] = date('F Y', $curr_time);
					echo '<optgroup label="'.date('F Y', $curr_time).'">';
				}
				$first_label = (intval((sizeof($timeline_array) * 0.4)) - 1) == -1 ? 0 : (intval((sizeof($timeline_array) * 0.4)) - 1);
				$first_label = 0;
				$selected = $k == $first_label ? 'selected="selected"' : '';
	
				$date_item = $dateFormat[$poll_index] == 'str' ? date('F j, Y', $curr_time) : date('d/m/Y', $curr_time);
	
				echo '<option '.$selected.' value="'.date('Y-m-d', $curr_time).'">'.$date_item.'</option>';
			}
			echo '</select>';
			echo '<select class="polling_select2" id="polling_select_'.$module_id.'_'.$poll_index.'_2" name="polling_select_'.$module_id.'_'.$poll_index.'_2">';
			$optionGroups = array();
			foreach ($timeline_array as $k => $curr_time) {
	
				if(!in_array(date('F Y', $curr_time),$optionGroups)) {
	
					if (sizeof($optionGroups) != 0)
						echo '</optgroup>';
	
					$optionGroups[] = date('F Y', $curr_time);
					echo '<optgroup label="'.date('F Y', $curr_time).'">';
				}
				$selected = $k == sizeof($timeline_array) - 1 ? 'selected="selected"' : '';
	
				$date_item = $dateFormat[$poll_index] == 'str' ? date('F j, Y', $curr_time) : date('d/m/Y', $curr_time);
	
				echo '<option '.$selected.' value="'.date('Y-m-d', $curr_time).'">'.$date_item.'</option>';
			}
			echo '</select></div>';
			echo '</div>';
			echo '</div>';
			$t_id = $polling_array[0] -> id_template;
			echo '<div class="sexy_clear">&nbsp;</div><div class="powered_by powered_by_'.$t_id.'">'.base64_decode('UG93ZXJlZCBCeQ==').' <span>'.base64_decode('U2V4eSBQb2xsaW5n').'</span></div><div class="sexy_clear">&nbsp;</div>';
			echo '</div></div>';
		}
	}
return ob_get_clean();
}
?>