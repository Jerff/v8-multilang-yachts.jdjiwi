<?php


cmfAjax::start();

if(!cmfRegister::getUser()->is()) {
	exit;
}
cmfLoad('user/cmfUserInfo');
$userRegister = new cmfUserInfo();
$userRegister->run1();

?>