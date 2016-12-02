<?php

$page = new cmfAdminController();

$main_list = $page->load('table_list_controller');
$page->run();


$this->assing('main_list', $main_list);
$this->assing('limitUrl', $main_list->getLimitUrl());
$this->assing('linkPage', $main_list->getLinkPage());

?>
