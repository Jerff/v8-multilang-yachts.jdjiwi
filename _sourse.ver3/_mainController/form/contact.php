<?php
cmfAjax::start();
$r = cmfregister::getRequest();


cmfLoad('form/cmfContactForm');
$contact = new cmfContactForm();
$contact->run();

?>