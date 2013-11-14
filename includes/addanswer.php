<?php
error_reporting(0);
header('Content-type: application/json');

global $wpdb;
parse_str($_POST['data'],$wpsxp_my_post);

//get ip address
$REMOTE_ADDR = null;
if(isset($_SERVER['REMOTE_ADDR'])) { $REMOTE_ADDR = $_SERVER['REMOTE_ADDR']; }
elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { $REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR']; }
elseif(isset($_SERVER['HTTP_CLIENT_IP'])) { $REMOTE_ADDR = $_SERVER['HTTP_CLIENT_IP']; }
elseif(isset($_SERVER['HTTP_VIA'])) { $REMOTE_ADDR = $_SERVER['HTTP_VIA']; }
else { $REMOTE_ADDR = 'Unknown'; }
$ip = $REMOTE_ADDR;

//get post data
$polling_id = (int)$wpsxp_my_post['polling_id'];
$autopublish = (int)$wpsxp_my_post['autopublish'];
$writeinto = (int)$wpsxp_my_post['writeinto'];
$answer = mysql_real_escape_string(strip_tags($wpsxp_my_post['answer']));
$answer = preg_replace('/sexydoublequestionmark/','??',$answer);

$voting_period = $wpsxp_my_post['voting_period'];
$date_now = strtotime("now");
$datenow = date("Y-m-d H:i:s", $date_now);

$countryname = (!isset($wpsxp_my_post['country_name']) || $wpsxp_my_post['country_name'] == '' || $wpsxp_my_post['country_name'] == '-' ) ? 'Unknown' : mysql_real_escape_string($wpsxp_my_post['country_name']);
$cityname = (!isset($wpsxp_my_post['city_name']) || $wpsxp_my_post['city_name'] == '' || $wpsxp_my_post['city_name'] == '-' ) ? 'Unknown' : mysql_real_escape_string($wpsxp_my_post['city_name']);
$regionname = (!isset($wpsxp_my_post['region_name']) || $wpsxp_my_post['region_name'] == '' || $wpsxp_my_post['region_name'] == '-' ) ? 'Unknown' : mysql_real_escape_string($wpsxp_my_post['region_name']);
$countrycode = (!isset($wpsxp_my_post['country_code']) || $wpsxp_my_post['country_code'] == '' || $wpsxp_my_post['country_code'] == '-' ) ? 'Unknown' : mysql_real_escape_string($wpsxp_my_post['country_code']);

if($writeinto == 1 || $autopublish == 0) {
	$published = $autopublish == 1 ? 1 : 0;
	mysql_query("INSERT INTO `".$wpdb->prefix."wpsxp_sexy_answers` (`id_poll`,`name`,`published`,`created`) VALUES ('$polling_id','$answer','$published','$datenow')");
	$insert_id = mysql_insert_id();
	
	mysql_query("INSERT INTO `".$wpdb->prefix."wpsxp_sexy_votes` (`id_answer`,`ip`,`date`,`country`,`city`,`region`,`countrycode`) VALUES ('$insert_id','$ip','$datenow','$countryname','$cityname','$regionname','$countrycode')");
	//set the cookie
	if($voting_period == 0) {
		$expire = time()+(60*60*24*365*2);//2 years
		setcookie("sexy_poll_$polling_id", $date_now, $expire, '/');
	}
	else {
		$expire_time = (float)$voting_period*60*60;
		$expire = (int)(time()+$expire_time);
		setcookie("sexy_poll_$polling_id", $date_now, $expire, '/');
	}
}
else {
	$insert_id = 0;
}

$ans = str_replace('\\','',htmlspecialchars(stripslashes($answer),ENT_QUOTES));
echo '[{"answer": "'.$ans.'", "id" : "'.$insert_id.'"}]';
//echo $answer;

exit();
?>