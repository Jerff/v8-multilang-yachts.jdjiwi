<?php


$page = new cmfAdminController();
$main_list = $page->load('table_data_controller');
$this->assing('filterTable', $main_list->filterTable());
$main_list->initTable();
$page->run();


$this->assing('main_list', $main_list);

?>
