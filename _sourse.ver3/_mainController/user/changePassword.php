<?php


cmfAjax::start();

if(!cmfRegister::getUser()->is()) {
	exit;
}
cmfLoad('user/cmfUserChangePassword');
$userEnter = new cmfUserChangePassword();
$userEnter->run1();



?>