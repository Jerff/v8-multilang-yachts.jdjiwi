<?php


cmfAjax::start();
$r = cmfregister::getRequest();


cmfLoad('request/cmfRequestArenda');
$request = new cmfRequestArenda();
$request->run();

?>