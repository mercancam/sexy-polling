<?php 
error_reporting(0);
header('Content-type: application/json');

$ip = $_GET[ip];
$url = 'http://api.ipinfodb.com/v3/ip-city/?key=74d13755d145cc686754a7d36e29eaf06e0e64fe2fcb8e1ac7ee9723f2eeb3b2&format=json&ip=' . $ip;
$ch = curl_init ($url) ;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
$output = curl_exec($ch) ;
curl_close($ch) ;

echo $output;
?>