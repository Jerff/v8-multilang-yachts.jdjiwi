<?php


$page = new cmfAdminController();
$main_list = $page->load('menu_pages_controller');
$page->run();


$this->assing('main_list', $main_list);

?>
