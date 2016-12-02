<?php

$page = new cmfAdminController();
$main_list = $page->load('param_group_search_controller');
$main_list->setFilter('group', cmfPages::getParam(1));
$page->run();

$this->assing('main_list', $main_list);

?>
