<?php

$page = new cmfAdminController();

$main_edit = $page->load('brokerage_yachts_edit_controller');
if (!$main_edit->getId() and cmfAdminMenu::getSubMenuId()) {
    $main_edit->setId(cmfAdminMenu::getSubMenuId());
}
$page->run();
if ($main_edit->notId())
    return cmfAdminNotRecord;

if ($main_edit->getId()) {
    cmfAdminMenu::setSubMenuId($main_edit->getId());
    cmfAdminMenu::setUserMenu('yachts');
}

$this->assing('main_edit', $main_edit);
list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

list($langForm, $langData) = $main_edit->current()->lang;
$this->assing('langForm', $langForm);
$this->assing('langData', $langData);
?>