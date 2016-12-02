<?php

$page = new cmfAdminController();

$main_list = $page->load('_administrator_group_list_controller');
$page->run();


$this->assing('main_list', $main_list);

?>
