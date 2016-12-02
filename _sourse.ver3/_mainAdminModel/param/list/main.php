<?php

$page = new cmfAdminController();

$main_list = $page->load('param_list_controller');
$page->run();


$this->assing('main_list', $main_list);

?>
