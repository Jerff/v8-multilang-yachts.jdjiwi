<?php

cmfAjax::start();
$r = cmfRegister::getRequest();

cmfLoad('user/cmfUserEnter');
$userEnter = new cmfUserEnter();
$userEnter->run();
?>