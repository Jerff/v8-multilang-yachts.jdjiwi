<?php

if(!defined('cmfPart'))
	define('cmfPart', 'main');


// конфигурация
require('_.config/config.php');
require('_.config/configProject.php');


// системный кеш
if(cmfComplile)  {
	require(cmfCompileLib . $includePath .'.php');
} else {

    require($includePath .'.php');
}
cmfPages::setBase($_b);
unset($_b);


///cmfDebug::setError();
//cmfDebug::setSql();
cmfCache::setPages();
//cmfCache::setData();


error_reporting(E_ALL);
set_error_handler(array('cmfDebug', 'errorHandler'));
register_shutdown_function(array('cmfDebug', 'end'));


// get_magic_quotes_gpc
cmfStripSlashesPost();



$admin = new cmfAdmin();
if($admin->is()) {
	if($admin->debugError==='yes')	cmfDebug::setError();
	if($admin->debugSql==='yes')	cmfDebug::setSql();
	if($admin->debugExplain==='yes')cmfDebug::setExplain();
	//if($admin->debugCache==='yes')	cmfCache::setPages();
	//cmfCache::setData($admin->debugCache==='yes');
}
unset($admin);

?>