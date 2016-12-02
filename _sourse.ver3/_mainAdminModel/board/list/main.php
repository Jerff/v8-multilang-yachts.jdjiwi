<?php

$page = new cmfAdminController();

$main_list = $page->load('board_list_controller');
$page->run();

$this->assing('listUser', $main_list->listUser());
$this->assing('search', htmlspecialchars(urldecode($main_list->getFilter('search'))));

$this->assing('main_list', $main_list);
$this->assing('limitUrl', $main_list->getLimitUrl());
$this->assing('linkPage', $main_list->getLinkPage());
?>