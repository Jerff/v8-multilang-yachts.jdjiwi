<?php

define('cmfPart', 'admin');

chdir(dirname(__FILE__));
define('cmfRoot', realpath(dirname(__FILE__) .'/../') .'/');


// конфигурация
require('_.config/config.php');
require('_.config/configProject.php');


if(cmfComplile)  {

	require(cmfCompileLib .'.includeAdmin.php');
} else {

	require('.includeAdmin.php');
}


set_error_handler(array('cmfDebug', 'errorHandler'));

$admin = new cmfAdmin();
if(!$admin->is()) {
	exit;
}
unset($admin);



$path = cmfRegister::getRequest()->getGet('path');
$number = cmfRegister::getRequest()->getGet('number');
if(!$path) {
	$type = explode('-', cmfRegister::getRequest()->getGet('type'));
    if(count($type)==5) {
        $path = $type[2];
        $number = $type[4];
        $type = $type[0];
        if($type=='All') $type =null;
        cmfRegister::getRequest()->setGet('type', $type[0]);
        cmfRegister::getRequest()->setGet('path', $path);
        cmfRegister::getRequest()->setGet('number', $number);
    }
}


$path = cmfWysiwyng::getPath($path, $number);
restore_error_handler();

return $path;

?>
