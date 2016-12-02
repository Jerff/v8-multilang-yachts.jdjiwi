<?php


$page = new cmfAdminController();
$main_edit = $page->load('user_edit_controller');
if(cmfAdminMenu::getSubMenuId()) {
	$main_edit->setId(cmfAdminMenu::getSubMenuId());
}
if($main_edit->getId()) {
	cmfUserModel::accesIs($main_edit->getId());
}
$page->run();
if($main_edit->notId()) return cmfAdminNotRecord;


$this->assing('main_edit', $main_edit);

list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

cmfAdminMenu::setSubMenuId($main_edit->getId());
$this->assing('userStat', $main_edit->getUserStat());
if($main_edit->getId()) {
    cmfAdminMenu::setUserMenu('param');
}

?>