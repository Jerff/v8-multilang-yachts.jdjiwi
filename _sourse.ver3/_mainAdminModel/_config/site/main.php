<?php


$page = new cmfAdminController();
$main = $page->load('_config_site_controller', 'site');
$page->run();
if($main->notId()) return cmfAdminNotRecord;
unset($page);


$this->assing('main_edit', $main);

list($form, $data) = $main->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

?>