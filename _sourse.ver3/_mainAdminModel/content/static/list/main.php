<?php

$page = new cmfAdminController();

$main_list = $page->load('content_static_list_controller');
$this->assing('filterSection', $main_list->filterSection());
$page->run();


$this->assing('main_list', $main_list);


?>
