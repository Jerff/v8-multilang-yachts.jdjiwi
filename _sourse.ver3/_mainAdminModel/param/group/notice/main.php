<?php

$page = new cmfAdminController();
$main_list = $page->load('param_group_notice_controller');
$view = $page->load('param_group_notice_view_controller', 'site');
$main_list->setFilter('group', cmfPages::getParam(1));
$page->run();


$this->assing('group', cmfPages::getParam(1));
$this->assing('main_list', $main_list);

$this->assing('view', $view);
list($form, $data) = $view->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

?>
