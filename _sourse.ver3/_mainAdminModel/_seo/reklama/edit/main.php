<?php

$page = new cmfAdminController();

$main_edit = $page->load('_seo_reklama_edit_controller');
$page->run();
if($main_edit->notId()) return cmfAdminNotRecord;


$this->assing('main_edit', $main_edit);

list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

?>