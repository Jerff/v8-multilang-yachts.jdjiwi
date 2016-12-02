<?php

if (!defined('cmfPart')) {
    if (isset($_SERVER['HTTP_HOST']) and ($_SERVER['HTTP_HOST'] === 'blog.dev.nsk-yachts.com.ua' or $_SERVER['HTTP_HOST'] === 'blog.nsk-yachts.com.ua')) {
        define('cmfPart', 'blog');
    } else {
        define('cmfPart', 'main');
    }
}


chdir(dirname(__FILE__));
define('cmfRoot', realpath(dirname(__FILE__) . '/../') . '/');

// конфигурация
require('_.config/config.php');
require('_.config/configProject.php');

// системный кеш
if (cmfComplile) {
    require(cmfCompileLib . '.includeMain.php');
} else {

    require('.includeMain.php');
}
//cmfLoadConfig('configSite');
cmfPages::setBase($_b);

unset($_b);



//cmfDebug::setError();
//cmfDebug::setSql();
//cmfDebug::setExplain();
cmfCache::setPages();
//cmfCache::setData();



error_reporting(E_ALL);
set_error_handler(array('cmfDebug', 'errorHandler'));
register_shutdown_function(array('cmfDebug', 'end'));


/* if(stripos($_SERVER['HTTP_HOST'], 'www.')===0) {
  cmfRedirectSeo('http://'. substr($_SERVER['HTTP_HOST'], 4) . $_SERVER['REQUEST_URI']);
  } */

// get_magic_quotes_gpc
//cmfStripSlashesPost();

$admin = cmfRegister::getAdmin();
if ($admin->is()) {
    if ($admin->debugError === 'yes')
        cmfDebug::setError();
    if ($admin->debugSql === 'yes')
        cmfDebug::setSql();
    if ($admin->debugExplain === 'yes')
        cmfDebug::setExplain();
    //if($admin->debugCache==='yes')	cmfCache::setPages();
    //cmfCache::setData($admin->debugCache==='yes');
} else {
    /*    if (cmfSession::get('isMobile')) {

      } else if (isset($_GET['enter']) and $_GET['enter'] === 'qwerty') {
      cmfSession::set('isMobile', 1);
      } else {

      echo 'Для просмотра необходимо войти в систему';
      exit;
      }
     */
}

//header("HTTP/1.0 404 Not Found");
unset($admin);
$sql = cmfRegister::getSql();
$url = $sql->placeholder("SELECT `new` FROM ?t WHERE `type`=? AND `url`=?", db_seo_redirect, cmfPart, $_SERVER['REQUEST_URI'])
        ->fetchRow(0);
if ($url) {
    cmfRedirectSeo($url);
}
//pre($_SERVER['REQUEST_URI']);

cmfDebug::getMemory();

$controler = new cmfTemplateMain();

//$t_url = parse_url($_SERVER['REQUEST_URI']);
//$t_url = $t_url['path'];
//if(strstr($t_url,'/yachtimages/') !== FALSE){
//	$yachtsId = 14;
//	$res = $sql->placeholder("SELECT id, name, image_main FROM ?t WHERE yachts=? AND visible='yes' ORDER BY main, pos DESC", db_arenda_yachts_foto, $yachtsId)->fetchAssocAll();
//}


echo $controler->main();

cmfDebug::getMemory();
?>