<?php

$page = new cmfAdminController();

$main_edit = $page->load('board_edit_controller');
if(isset($_GET['moder'])) {
    $main_edit->moder();
}
$page->run();
if($main_edit->notId()) return cmfAdminNotRecord;

$this->assing('listUser', $main_edit->listUser());

$this->assing('main_edit', $main_edit);

list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

$this->assing('param', cmfGlobal::get('$param'));
?>