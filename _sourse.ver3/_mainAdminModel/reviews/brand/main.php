<?php


$page = new cmfAdminController();
$main_list = $page->load('reviews_brand_controller');
$page->run();


$this->assing('main_list', $main_list);

?>
