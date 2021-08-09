<?php
//ini_set('display_errors', 1);
date_default_timezone_set("UTC");

function log_access($log_dir=null) {

	$log_dir = !is_null($log_dir) ? rtrim($log_dir,'/')  : __DIR__ .'/access';

	$access_log = $log_dir .'/'.date("Ymd",time()+28800).'.log';

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

	];

	$messages_arr = [
		'local_time'=>date("H:i:s",time()+28800),
		'user_ip'=> isset($_SERVER['HTTP_CDN_SRC_IP'])? $_SERVER['HTTP_CDN_SRC_IP'] : $_SERVER['REMOTE_ADDR'].'(directly)',
		'request_method'=>$_SERVER['REQUEST_METHOD'],
		'user_agent'=> $_SERVER['HTTP_USER_AGENT'],
		'request_uri'=>$_SERVER['REQUEST_URI'],
		'allow'=>'pass',

	];


	if(in_array($messages_arr['user_ip'],$black_ips)) {
		$messages_arr['allow'] = 'block';
	}
	if(isset($_POST) && !empty($_POST) ){
		$messages_arr['post_args'] = http_build_query($_POST);
	}
	file_put_contents($access_log,implode(' - ', $messages_arr ) . PHP_EOL,FILE_APPEND);

	if($messages_arr['allow'] == 'block'){
		exit();
	}

}
log_access();
