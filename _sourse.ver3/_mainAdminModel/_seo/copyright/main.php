<?php


$page = new cmfAdminController();

$main_edit = $page->load('_seo_copyright_controller', 'main');
$page->run();



$this->assing('main_edit', $main_edit);
list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

?>