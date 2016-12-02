<?php


cmfLoad('system/cmfString');
cmfLoad('system/javascript');
cmfLoad('system/match');

function get($v, $k, $d=null) {
	if(isset($v[$k])) return $v[$k];
	else return $d;
}
function get2($v, $k, $k2, $d=null) {
	if(isset($v[$k][$k2])) return $v[$k][$k2];
	else return $d;
}
function get3($v, $k, $k2, $k3, $d=null) {
	if(isset($v[$k][$k2][$k3])) return $v[$k][$k2][$k3];
	else return $d;
}


function cmfGetMicrotime() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}



function cmfGetIp() {
	return empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['HTTP_X_FORWARDED_FOR'];
}
function cmfGetIpInt() {
	return (int)ip2long(cmfGetIp());
}

function cmfGetProxy() {
	return empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? '' :$_SERVER['REMOTE_ADDR'];
}
function cmfGetProxyInt() {
	return (int)ip2long(cmfGetProxy());
}

?>