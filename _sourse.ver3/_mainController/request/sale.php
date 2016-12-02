<?php


cmfAjax::start();
$r = cmfregister::getRequest();


cmfLoad('request/cmfRequestSale');
$request = new cmfRequestSale();
$request->run();

?>