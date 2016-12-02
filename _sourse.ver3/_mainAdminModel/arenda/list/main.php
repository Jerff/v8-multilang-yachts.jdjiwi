<?php


$page = new cmfAdminController();
$main_list = $page->load('arenda_list_controller');
$this->assing('filterSection', $main_list->filterSection());
$page->run();

$this->assing('isTree', (bool)$main_list->getFilter('parent'));

$this->assing('main_list', $main_list);

?>
