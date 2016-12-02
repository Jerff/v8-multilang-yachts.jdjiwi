<?php


cmfAjax::start();
cmfLoad('user/cmfUserRegister');
$userRegister = new cmfUserRegister();
$userRegister->run1();

?>