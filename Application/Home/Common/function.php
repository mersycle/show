<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*发生短信*/
function send_set(){
	set_time_limit(0);
	header("Content-Type: text/html; charset=UTF-8");
	Vendor('Nusoap.Client');
	$gwUrl = C('YM_WGURL');
	$serialNumber = C('YM_SERIAL');
	$password = C('YM_PWD');
	$sessionKey = C('YM_SESSION_KEY');
	$connectTimeOut = 2;
	$readTimeOut = 10;
		$proxyhost = false;
		$proxyport = false;
		$proxyusername = false;
		$proxypassword = false; 
	$client = new Client($gwUrl,$serialNumber,$password,$sessionKey,$proxyhost,$proxyport,$proxyusername,$proxypassword,$connectTimeOut,$readTimeOut);
	$client->setOutgoingEncoding("UTF-8");
	return $client;
}

function sendSMS($mobile=array(),$content){
	$client = send_set();
	$contents=strpos('start'.$content,'【小人请罪】')===false?'【小人请罪】'.$content:$content;
        
	$statusCode = $client->sendSMS($mobile,$contents);
	return $statusCode;
}
