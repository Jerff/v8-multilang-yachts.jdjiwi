<?php


cmfAjax::start();
$r = cmfregister::getRequest();


cmfLoad('user/cmfUserRecoverPassword');
$userEnter = new cmfUserRecoverPassword();
$userEnter->run();


?>