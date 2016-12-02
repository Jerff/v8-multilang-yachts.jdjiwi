<?php


$page = new cmfAdminController();
$main_edit = $page->load('_pages_admin_edit_controller');

$r = $main_edit->getRequest();
if($parent = $main_edit->getId()) {
	$r->setGet('list', $parent);
	$r->setGet('parent', $parent);
}
if($new = $r->isGet('add')) {
	$r->setGet('add', null);
	$r->setGet('id', null);
}
unset($r);


if(!$new) {
	$main_list = $page->load('_pages_admin_list_controller');
}
$page->run();


if(!$new) {
	$this->assing('count', $main_list->getCount());
	$this->assing('main_list', $main_list);
}
$this->assing('path', $main_edit->path());
unset($page);


$this->assing('id', $main_edit->getId());
$this->assing('new', $new);
$this->assing('type', cmfGlobal::get('pageType'));


list($form, $data) = $main_edit->current()->main;
$this->assing('main_edit', $main_edit);
$this->assing('form', $form);
$this->assing('data', $data);


?>
