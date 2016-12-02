<?php

$page = new cmfAdminController();
$main_edit = $page->load('brokerage_type_edit_controller');
if (!$main_edit->getId() and cmfAdminMenu::getSubMenuId()) {
    $main_edit->setId(cmfAdminMenu::getSubMenuId());
}
if ($main_edit->getId()) {
    cmfAdminMenu::setSubMenuId($main_edit->getId());
    cmfAdminMenu::setUserMenu('slider');
    $main_edit->setFilter('pageData', 'sale/new/type');
}
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