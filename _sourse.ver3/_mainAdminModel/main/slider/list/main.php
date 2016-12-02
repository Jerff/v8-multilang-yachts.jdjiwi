<?php

if (!cmfAdminMenu::getSubMenuId()) {
    return cmfAdminNotRecord;
}
cmfAdminMenu::setUserMenu('slider');

$page = new cmfAdminController();
$main_edit = $page->load('main_slider_edit_controller');
$this->assing('id', $main_edit->getId());

$main_list = $page->load('main_slider_list_controller');
$page->run();


$this->assing('main_list', $main_list);


$this->assing('main_edit', $main_edit);
list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

list($langForm, $langData) = $main_edit->current()->lang;
$this->assing('langForm', $langForm);
$this->assing('langData', $langData);
?>