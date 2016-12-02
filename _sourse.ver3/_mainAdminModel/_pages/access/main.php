<?php

$_group = cmfModulLoad('_administrator_group_list_db')->getNameList();


$r = cmfRegister::getRequest();
$group = $r->getGet('group');
if(!isset($_group[$group])) $group = key($_group);
$_group[$group]['sel'] = true;
$r->setGet('group', $group);

$this->assing('url', $url = cmfGetAdminUrl('/admin/pages/access/') .'&'. cmfUrlParam::toUrl($r->getGetAll()));
foreach($_group as $key=>$value) {
	$_group[$key]['url'] = $url .'&group='. $key;
}
$this->assing('_group', $_group);



$page = new cmfAdminController();
$main_list = $page->load('_pages_access_controller');
$page->run();
$this->assing('main_list', $main_list);


?>