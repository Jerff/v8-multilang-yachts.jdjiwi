<?php


$page = new cmfAdminController();
$main_edit = $page->load('_cache_data_controller');

if(cmfAjax::is()) {
	if(cmfAjax::isCommand()) {
        $main_edit->runCommand();
	}
}
if($main_edit->getFilter('command')) {
	$main_edit->runCommand($main_edit->getFilter('command'));
	exit;
}
$page->run();

$this->assing('main_edit', $main_edit);


?>