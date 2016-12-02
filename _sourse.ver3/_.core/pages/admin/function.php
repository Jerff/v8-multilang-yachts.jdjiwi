<?php


function cmfGetAdminUrl($page, $param=null) {
	$uri = cmfPages::getPageFlag($page, 'url');
	if(!$uri) return false;
	return cmfPages::getBase('admin') . cmfLang::getUri() . cmfUrl::reform($uri, $param) .'#';
}


function cmfGetAdminCommand($page, $param=null) {
	$uri = cmfPages::getPageFlag($page, 'url');
	if(!$uri) return false;
	return cmfPages::getBase('admin') . cmfLang::getUri() . '/?url='. cmfUrl::reform($uri, $param);
}


?>
