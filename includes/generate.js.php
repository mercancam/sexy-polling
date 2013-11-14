<?php
error_reporting(0);
header('Content-Type: application/javascript');

global $wpdb;
$wpsxp_userRegistered =  (int)$_REQUEST['wpsxp_userRegistered'];

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

$poll_id = isset($_GET['id_poll']) ? (int)$_GET['id_poll'] : 0;
$module_id = 0;
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
		
		//timeline
		$polling_select_id[$poll_index]['select1'] = 'polling_select_'.$module_id.'_'.$poll_index.'_1';
		$polling_select_id[$poll_index]['select2'] = 'polling_select_'.$module_id.'_'.$poll_index.'_2';
		
		$new_answer_bar_index = ($k + 1) % 20 + 1;

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
		$num_rows = $db->num_rows;
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
		
		$multiple_answers = $polling_array[0]->multiple_answers;
		$multiple_answers_info_array[$poll_index] = $multiple_answers;
	}
}

if(sizeof($custom_styles) > 0)
	foreach ($custom_styles as $poll_id => $styles_list) {
	$styles_array = explode('|',$styles_list);
	foreach ($styles_array as $val) {
		$arr = explode('~',$val);
		$styles_[$poll_id][$arr[0]] = $arr[1];
	}
}


//create javascript animation styles array
$wpsxp_jsinclude = 'if (typeof animation_styles === \'undefined\') { var animation_styles = new Array();};';
if(sizeof($styles_) > 0)
	foreach ($styles_ as $poll_id => $styles) {
	$s1 = $styles[12];//backround-color
	$s2 = $styles[73];//border-color
	$s3 = $styles[68].' '.$styles[69].'px '.$styles[70].'px '.$styles[71].'px '.$styles[72].'px '.$styles[11];//box-shadow
	$s4 = $styles[74].'px';//border-top-left-radius
	$s5 = $styles[75].'px';//border-top-right-radius
	$s6 = $styles[76].'px';//border-bottom-left-radius
	$s7 = $styles[77].'px';//border-bottom-right-radius
	$s8 = $styles[0];//static color
	$s9 = $styles[68];//shadow type
	$s9 = $styles[68];//shadow type
	$s10 = $styles[90];//navigation bar height
	$s11 = $styles[251];//Answer Color Inactive
	$s12 = $styles[270];//Answer Color Active
	$wpsxp_jsinclude .= 'animation_styles["'.$module_id.'_'.$poll_id.'"] = new Array("'.$s1.'", "'.$s2.'", "'.$s3.'", "'.$s4.'", "'.$s5.'", "'.$s6.'", "'.$s7.'","'.$s8.'","'.$s9.'","'.$s10.'","'.$s11.'","'.$s12.'");';
}

//new version added
//add voting period to javascript
$wpsxp_jsinclude .= ' if (typeof voting_periods === \'undefined\') { var voting_periods = new Array();};';
if(sizeof($voting_periods) > 0)
	foreach ($voting_periods as $poll_id => $voting_period) {
	$wpsxp_jsinclude .= 'voting_periods["'.$module_id.'_'.$poll_id.'"] = "'.$voting_period.'";';
}

$wpsxp_jsinclude .= 'if (typeof sexyPolling_words === \'undefined\') { var sexyPolling_words = new Array();};';
foreach ($polling_words as $k => $val) {
	$wpsxp_jsinclude .= 'sexyPolling_words["'.$k.'"] = "'.$val.'";';
}
$wpsxp_jsinclude .= 'if (typeof multipleAnswersInfoArray === \'undefined\') { var multipleAnswersInfoArray = new Array();};';
foreach ($multiple_answers_info_array as $k => $val) {
	$wpsxp_jsinclude .= 'multipleAnswersInfoArray["'.$k.'"] = "'.$val.'";';
}
$wpsxp_jsinclude .= 'var newAnswerBarIndex = "'.$new_answer_bar_index.'";';
$wpsxp_jsinclude .= 'var sexyIp = "'.$sexyip.'";';
$wpsxp_jsinclude .= 'var sexyPath = "'.plugin_dir_url( __FILE__ ).'";';

$wpsxp_jsinclude .= 'if (typeof sexyPollingIds === \'undefined\') { var sexyPollingIds = new Array();};';
$k = 0;
foreach ($polling_select_id as $poll_id) {
	$wpsxp_jsinclude .= 'sexyPollingIds.push(Array("'.$poll_id["select1"].'","'.$poll_id["select2"].'"));';
	$k ++;
}
$wpsxp_jsinclude .= 'if (typeof votingPermissions === \'undefined\') { var votingPermissions = new Array();};';
foreach ($voting_permissions as $key => $voting_permission) {
	$message = $voting_permission ? 'allow_voting' : $polling_words['24'];
	$wpsxp_jsinclude .= 'votingPermissions.push("'.$key.'");';
	$wpsxp_jsinclude .= 'votingPermissions["'.$key.'"]="'.$message.'";';
}
$wpsxp_jsinclude .= 'if (typeof votedIds === \'undefined\') { var votedIds = new Array();};';
foreach (array_keys($voted_ids) as $voted_id) {
	$hoursdiff = $voted_ids[$voted_id];
	$estimated_days = (int) ($hoursdiff / 24);
	$estimated_hours = ((int) $hoursdiff) % 24;
	$estimated_minutes = ((int) ($hoursdiff * 60)) % 60;
	$estimated_seconds = (((int) ($hoursdiff * 3600)) % 3600) % 60;

	$est_time = $estimated_days > 99 ? 'never' : $hoursdiff;
	$wpsxp_jsinclude .= 'votedIds.push(Array("'.$voted_id.'","'.$module_id.'","'.$est_time.'"));';
}
$wpsxp_jsinclude .= 'if (typeof startDisabledIds === \'undefined\') { var startDisabledIds = new Array();};';
foreach ($start_disabled_ids as $start_disabled_data) {
	$hoursdiff = $start_disabled_data['2'];
	$estimated_days = (int) ($hoursdiff / 24);
	$est_time = $estimated_days > 99 ? 'never' : $hoursdiff;
	$wpsxp_jsinclude .= 'startDisabledIds.push(Array("'.$start_disabled_data[0].'","'.$start_disabled_data[1].'","'.$module_id.'","'.$est_time.'"));';
}
$wpsxp_jsinclude .= 'if (typeof endDisabledIds === \'undefined\') { var endDisabledIds = new Array();};';
foreach ($end_disabled_ids as $end_disabled_data) {
	$wpsxp_jsinclude .= 'endDisabledIds.push(Array("'.$end_disabled_data[0].'","'.$end_disabled_data[1].'","'.$module_id.'"));';
}
$wpsxp_jsinclude .= 'if (typeof allowedNumberAnswers === \'undefined\') { var allowedNumberAnswers = new Array();};';
foreach ($number_answers_array as $poll_id => $number_answers_data) {
	$wpsxp_jsinclude .= 'allowedNumberAnswers.push("'.$poll_id.'");';
	$wpsxp_jsinclude .= 'allowedNumberAnswers["'.$poll_id.'"]="'.$number_answers_data.'";';
}

$wpsxp_jsinclude .= 'if (typeof autoOpenTimeline === \'undefined\') { var autoOpenTimeline = new Array();};';
foreach ($autoOpenTimeline as $poll_id => $v) {
	$wpsxp_jsinclude .= 'autoOpenTimeline.push("'.$poll_id.'");';
	$wpsxp_jsinclude .= 'autoOpenTimeline["'.$poll_id.'"]="'.$v.'";';
}

$wpsxp_jsinclude .= 'if (typeof autoAnimate === \'undefined\') { var autoAnimate = new Array();};';
foreach ($autoAnimate as $poll_id => $v) {
	$wpsxp_jsinclude .= 'autoAnimate.push("'.$poll_id.'");';
	$wpsxp_jsinclude .= 'autoAnimate["'.$poll_id.'"]="'.$v.'";';
}

$wpsxp_jsinclude .= 'if (typeof sexyAutoPublish === \'undefined\') { var sexyAutoPublish = new Array();};';
foreach ($autoPublish as $poll_id => $v) {
	$wpsxp_jsinclude .= 'sexyAutoPublish.push("'.$poll_id.'");';
	$wpsxp_jsinclude .= 'sexyAutoPublish["'.$poll_id.'"]="'.$v.'";';
}

$wpsxp_jsinclude .= 'if (typeof dateFormat === \'undefined\') { var dateFormat = new Array();};';
foreach ($dateFormat as $poll_id => $v) {
	$wpsxp_jsinclude .= 'dateFormat.push("'.$poll_id.'");';
	$wpsxp_jsinclude .= 'dateFormat["'.$poll_id.'"]="'.$v.'";';
}

$wpsxp_jsinclude .= 'if (typeof sexyAnimationTypeBar === \'undefined\') { var sexyAnimationTypeBar = new Array();};';
foreach ($sexyAnimationTypeBar as $poll_id => $v) {
	$wpsxp_jsinclude .= 'sexyAnimationTypeBar.push("'.$poll_id.'");';
	$wpsxp_jsinclude .= 'sexyAnimationTypeBar["'.$poll_id.'"]="'.$v.'";';
}

$wpsxp_jsinclude .= 'if (typeof sexyAnimationTypeContainer === \'undefined\') { var sexyAnimationTypeContainer = new Array();};';
foreach ($sexyAnimationTypeContainer as $poll_id => $v) {
	$wpsxp_jsinclude .= 'sexyAnimationTypeContainer.push("'.$poll_id.'");';
	$wpsxp_jsinclude .= 'sexyAnimationTypeContainer["'.$poll_id.'"]="'.$v.'";';
}

$wpsxp_jsinclude .= 'if (typeof sexyAnimationTypeContainerMove === \'undefined\') { var sexyAnimationTypeContainerMove = new Array();};';
foreach ($sexyAnimationTypeContainerMove as $poll_id => $v) {
	$wpsxp_jsinclude .= 'sexyAnimationTypeContainerMove.push("'.$poll_id.'");';
	$wpsxp_jsinclude .= 'sexyAnimationTypeContainerMove["'.$poll_id.'"]="'.$v.'";';
}

$wpsxp_jsinclude .= 'sexypolling_admin_path = "'.admin_url().'";';

echo $wpsxp_jsinclude;