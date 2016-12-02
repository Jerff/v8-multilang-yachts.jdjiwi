<?php

$page = new cmfAdminController();

$main_list = $page->load('_mail_var_controller');
$main_edit = $page->load('_mail_var_config_controller');
$main_edit->setId(1);
$page->run();



$this->assing('main_list', $main_list);

$this->assing('main_edit', $main_edit);
list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

?>
