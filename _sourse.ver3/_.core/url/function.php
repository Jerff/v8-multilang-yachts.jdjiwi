<?php


cmfLoad('url/cmfUrlParam');
cmfLoad('url/cmfUrl');
// редирект
function cmfRedirect($u) {
	header('Location: '. $u);
	exit;
}

function cmfRedirectSeo($u) {	
	
	header('HTTP/1.1 301 Moved Permanently: '. $u);
	header('Location: '. $u);
	exit;
}

?>