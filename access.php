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
				
	];
	
	$black_ip_groups = [
		'64.64.102.',
		'196.16.82.',
		'47.91.255.',
		'101.32.36.',
		'94.74.112.',
	];
	

	$messages_arr = [
		'local_time'=>date("H:i:s",$local_ts),
		'user_ip'=> isset($_SERVER['HTTP_CDN_SRC_IP'])? $_SERVER['HTTP_CDN_SRC_IP'] : $_SERVER['REMOTE_ADDR'].'(directly)',
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
		exit();
	}

}
log_access();
