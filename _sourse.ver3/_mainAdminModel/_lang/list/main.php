<?php


$page = new cmfAdminController();
$main_list = $page->load('_lang_list_controller');
$main_edit = $page->load('_lang_list_config_controller', 'lang');
$page->run();



$this->assing('main_list', $main_list);

$this->assing('main_edit', $main_edit);
list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

list($form, $data) = $main_edit->current()->section;
$this->assing('formSection', $form);
$this->assing('dataSection', $data);

?>
