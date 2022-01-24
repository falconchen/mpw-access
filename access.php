<?php
//ini_set('display_errors', 1);
date_default_timezone_set("UTC");

function log_access($log_dir=null) {

	$log_dir = !is_null($log_dir) ? rtrim($log_dir,'/')  : __DIR__ .'/access';

	$local_ts = time() + 28800;

	$access_log = $log_dir .'/'.date("Ymd",$local_ts).'.log';

	if(!is_dir(dirname($access_log))) {
		mkdir(dirname($access_log),0755,true);
	}
	$black_ips = [
		//'149.129.68.64', //for test only
		
		'38.108.108.178',
		'3.214.166.117',
		'47.90.35.53',
		'3.88.136.51',
		'34.80.22.126',
		'104.144.199.163',
		'35.153.55.178',
		'54.158.208.170',
		'3.82.3.96',
		'54.166.234.25',
		'34.238.171.190',
		'18.234.202.160',
		'52.55.227.18',
		'35.201.206.6',
		'47.243.198.134',
		'124.156.166.170',
		'95.217.7.161',
		'35.203.254.46',
		'13.229.208.88',
		'83.145.252.169',
		'202.39.156.137',
		'45.57.148.21',
		'104.233.179.113',	
		'78.47.67.6',
		'49.12.216.246',
		'54.151.127.95',
		'149.202.169.80',
		'104.239.248.101',
		'129.226.172.206',
		'78.47.26.119',
		'110.249.208.135',
		'43.249.36.39',
		'146.247.137.99',
		'143.198.202.184',
		'66.102.6.220',
		'144.168.177.129',
		'185.254.106.128',
		'158.46.162.224',
		'191.96.250.229',
		'45.130.123.252',
		'191.101.82.45',
		'89.40.112.39',
		'45.134.113.54',
		'89.184.193.129',
		'94.176.124.106',
		'185.28.182.79',
		'45.84.229.38',
		'202.181.20.194',
		'178.171.99.30',
		'119.12.206.26',
		'121.91.81.4',
		'194.53.70.6',
		'31.14.219.64',			
		'47.88.11.172',
		'35.171.88.156',
		'37.218.243.82',
	];
	
	$black_ip_groups = [

		//'66.249.79.',//google clawer
		'42.98.49.',
		'5.62.62.',
		'23.229.',
	    '14.136.159.',
		'64.64.102.',
		'196.16.82.',
		'47.91.255.',
		'101.32.36.',
		'94.74.112.',
		'3.86.138.',//rogerbot
		'42.200.195.',
		'47.242.',
		'47.243.',
		'178.33.231.',
		'49.12.237.',
		'65.108.',
		'95.216.',
		'95.217.',
		'20.69.242.',
		'154.37.',
		'154.9.',
		'20.108.23.',
		'118.107.245.',
		'45.146.164.',
		'43.130.40.',
		'5.9.',
		'45.57.148.',
		'23.254.78.',
		'35.229.151.',
		'138.128.74.',
		'124.156.134.',
		'17.121.114.',
		'79.171.81.',
		'61.238.100.',
		'34.229.67.',
		'210.61.12.',
	//	'66.249.79.',//google bot?
		'195.23.32.',
		'35.240.211.',
		'72.14.199.',
		'18.207.1.',
		'114.119.1',
		'14.0.154.',
		'167.114.103.',
		'191.101.',
		
	];
	
	if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])){
	    $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
	}elseif(isset($_SERVER['HTTP_CDN_SRC_IP'])){
	    $user_ip = $_SERVER['HTTP_CDN_SRC_IP'];
	}else{
	    $user_ip = $_SERVER['REMOTE_ADDR'].'(directly)';
	}

	$messages_arr = [
		'local_time'=>date("H:i:s",$local_ts),
		'user_ip'=>$user_ip, 
		'request_method'=>$_SERVER['REQUEST_METHOD'],
		'user_agent'=> $_SERVER['HTTP_USER_AGENT'],
		'request_uri'=>$_SERVER['REQUEST_URI'],
		'allow'=>'pass',

	];


	if(in_array($messages_arr['user_ip'],$black_ips)) {
		$messages_arr['allow'] = 'blocked';
	}else{

		foreach($black_ip_groups as $ip_start){
			if(strpos($messages_arr['user_ip'],$ip_start) === 0){
				$messages_arr['allow'] = 'blocked';
			}
		}

	}


	if(isset($_POST) && !empty($_POST) ){
		$messages_arr['post_args'] = http_build_query($_POST);
	}


	file_put_contents($access_log,implode(' - ', $messages_arr ) . PHP_EOL,FILE_APPEND);

/*	if(strpos($messages_arr['user_ip'],'directly')===false){
		exit();
	}
*/

	
	if($messages_arr['allow'] == 'blocked'){
		 header('HTTP/1.1 404 Not Found');
		 header("status: 404 Not Found");
		 exit();
	}

}
log_access();
