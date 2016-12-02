<?php


$page = new cmfAdminController();
$main_list = $page->load('menu_footer_controller');
$page->run();


$this->assing('main_list', $main_list);

?>
