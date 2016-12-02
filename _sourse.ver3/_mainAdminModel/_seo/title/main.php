<?php

$page = new cmfAdminController();

$main_edit = $page->load('_seo_title_controller');
$config = $page->load('_seo_title_config_controller', 'seo');
$main_edit->setId(urldecode($main_edit->getId()));
$page->run();


$this->assing('main_edit', $main_edit);
list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

$this->assing('config', $config);
list($form, $data) = $config->current()->main;
$this->assing('configForm', $form);

?>
