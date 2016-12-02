<?php

define('cmfPart', 'controller');

chdir(dirname(__FILE__));
define('cmfRoot', realpath(dirname(__FILE__) .'/../') .'/');

$includePath = '.includeController';
include('configController.php');


$file = str_replace(cmfControllerUrl, '', 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
$file = preg_replace('~(([a-z\.\/]*)\/).*~is', '$2', $file);
$file = cmfController . str_replace(array('..', '.'), '', $file) .'.php';
if(!is_file($file)) exit;


if(cmfComplile<2) {
	return require($file);
}


$file2 = cmfCompileController . urlencode($file) . '.php';


if(file_exists($file)) {
	if(cmfComplile==3) return require($file2);
	else {
		if(filemtime($file) < filemtime($file2)) return require($file2);
	}
}
$compile = new cmfCompile();
file_put_contents($file, $compile->compile($file, true));
unset($compile, $file);


return require($file2);

?>