<?php
//ini_set('display_errors', 1);
//exit();


function log_access($log_dir=null) {

	$log_dir = !is_null($log_dir) ? rtrim($log_dir,'/')  : __DIR__ .'/access';

	$local_ts = time() + 28800;

	$access_log = $log_dir .'/'.date("Ymd",$local_ts).'.log';

	if(!is_dir(dirname($access_log))) {
		mkdir(dirname($access_log),0755,true);
	}
	
	$black_ips = [
		
		//'43.154.73.172', //for test only
		'42.200.195.84',
		
		'180.235.169.21',
		'64.124.8.28',		
		'210.61.46.124',
		'112.120.122.191',	
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
		'185.206.129.203',
		'54.190.148.235',
		'208.185.238.58',
		'34.145.187.238',
		'185.234.7.116',
		'34.80.33.211',
		'154.38.26.97',
		'5.255.98.156',
		'223.19.122.71',
		'123.203.8.126',
		'185.107.70.56',
		'185.130.47.58',
		'210.177.253.130',
		'14.136.255.83',
		'59.148.180.180',
		'185.213.82.245',
		'223.16.43.107',
		'122.100.164.99',
		'14.199.216.249',
		'58.176.228.162',
		'109.239.229.234',
		'14.136.255.174',
		'38.18.13.163',
		'134.73.50.195',
	];
	
	$black_ip_groups = [
		#'66.249.',//google clawer

		'74.125.215.',//google producer
		'64.233.172.',//google producer2,
		'140.238.94.',

        '34.245.62.',
		'176.34.149.',
		'54.74.228.',
		'3.250.134.',
		'34.245.62.',
		
		'185.191.171.',
		'132.145.',
		'18.232.112.',
		'54.36.148.',
		'34.86.177.',
		'35.153.158.',
		'152.67.138.',
		'54.36.149.',
        '67.222.158.',
		'47.91.225.',
		'64.124.8.',
		'64.62.243.',
		'107.178.200.',
		'42.98.49.',
		'185.206.128.',
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
		//'66.249.79.',//google bot?
		'195.23.32.',
		'35.240.211.',
		'72.14.199.',
		'18.207.1.',
		'114.119.1',
		'14.0.154.',
		'167.114.103.',
		'191.101.',
		'45.195.74.',
		'210.3.196.',
		//'173.252.', //facebookexternalhit
		
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

#	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'pvc-check-post'){
#	   $message_arr['allow'] = 'blocked';
#		exit;
#	}

	file_put_contents($access_log,implode(' - ', $messages_arr ) . PHP_EOL,FILE_APPEND);

/*	if(strpos($messages_arr['user_ip'],'directly')===false){
		exit();
	}
*/

	
	if($messages_arr['allow'] == 'blocked'){
		 header('HTTP/1.1 404 Not Found');
		 header("status: 404 Not Found");

		 #header( $_SERVER["SERVER_PROTOCOL"].' 503' );
		 exit();
	}

}

function limit_fb($log_dir=null){

	$log_dir = !is_null($log_dir) ? rtrim($log_dir,'/')  : __DIR__ .'/access';
	if( !empty( $_SERVER['HTTP_USER_AGENT'] ) && strpos(  $_SERVER['HTTP_USER_AGENT'], 'facebookexternalhit' ) === 0 ) {
		$fbTmpFile = $log_dir.'/facebookexternalhit.txt';
		if( $fh = fopen( $fbTmpFile, 'c+' ) ) {
			$lastTime = fread( $fh, 100 );
			$microTime = microtime( TRUE );
			// check current microtime with microtime of last access
			if( $microTime - $lastTime < FACEBOOK_REQUEST_THROTTLE ) {
				// bail if requests are coming too quickly with http 503 Service Unavailable
				header( $_SERVER["SERVER_PROTOCOL"].' 503' );
				die;
			} else {
				// write out the microsecond time of last access
				rewind( $fh );
				fwrite( $fh, $microTime );
			}
			fclose( $fh );
		} else {
			header( $_SERVER["SERVER_PROTOCOL"].' 429' );
			die;
		}
	}

}
function limit_gb($log_dir=null){


	$log_dir = !is_null($log_dir) ? rtrim($log_dir,'/')  : __DIR__ .'/access';
	if( !empty( $_SERVER['HTTP_USER_AGENT'] ) && stripos(  $_SERVER['HTTP_USER_AGENT'], 'googlebot' ) !==false ) {
		$fbTmpFile = $log_dir.'/googlebot.txt';
		if( $fh = fopen( $fbTmpFile, 'c+' ) ) {
			$lastTime = fread( $fh, 100 );
			$microTime = microtime( TRUE );
			// check current microtime with microtime of last access
			if( $microTime - $lastTime < GB_REQUEST_THROTTLE ) {
				// bail if requests are coming too quickly with http 503 Service Unavailable
				header( $_SERVER["SERVER_PROTOCOL"].' 503' );
				die;
			} else {
				// write out the microsecond time of last access
				rewind( $fh );
				fwrite( $fh, $microTime );
			}
			fclose( $fh );
		} else {
			header( $_SERVER["SERVER_PROTOCOL"].' 429' );
			die;
		}
	}

}



date_default_timezone_set("UTC");
define( 'FACEBOOK_REQUEST_THROTTLE', 2.0 ); // Number of seconds permitted between each hit from facebookexternalhit
define( 'GB_REQUEST_THROTTLE', 2.0 ); // Number of seconds permitted between each hit from googlebot
limit_fb();
limit_gb();
log_access();


