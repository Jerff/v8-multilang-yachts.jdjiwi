<?php


$page = new cmfAdminController();

$main_edit = $page->load('_seo_sitemap_config_controller', 'sitemap');
$main_list = $page->load('_seo_sitemap_list_controller');
$page->run();

$this->assing('main_list', $main_list);
$this->assing('main_edit', $main_edit);
list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

?>