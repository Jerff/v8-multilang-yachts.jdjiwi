<?php

$page = new cmfAdminController();
$main_list = $page->load('blog_list_controller');
$this->assing('filterAutor', $main_list->filterAutor());
$page->run();

$this->assing('main_list', $main_list);
$this->assing('limitUrl', $main_list->getLimitUrl());
$this->assing('linkPage', $main_list->getLinkPage());

?>
