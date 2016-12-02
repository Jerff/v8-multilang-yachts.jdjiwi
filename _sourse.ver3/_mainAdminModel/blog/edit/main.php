<?php

$page = new cmfAdminController();

$main_edit = $page->load('blog_edit_controller');
$page->run();
if ($main_edit->notId())
    return cmfAdminNotRecord;


$this->assing('main_edit', $main_edit);
list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

list($langForm, $langData) = $main_edit->current()->lang;
$this->assing('langForm', $langForm);
$this->assing('langData', $langData);

?>