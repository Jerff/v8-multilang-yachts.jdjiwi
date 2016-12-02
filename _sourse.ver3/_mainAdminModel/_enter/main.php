<?php

$page = new cmfAdminController();

$main = $page->load('_enter_controller');
$page->run();
unset($page);

$this->assing('enter', $main);
$this->assing('ajaxUrl', cmfAjax::getUrl());

list($form) = $main->current()->main;
$this->assing('form', $form);

?>