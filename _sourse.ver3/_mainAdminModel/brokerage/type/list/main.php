<?php

//REPLACE `s03_brokerage_type` SELECT `id`,'yes',`url_type` , `type`FROM 2011_01_old.yacht_type

$page = new cmfAdminController();
$main_list = $page->load('brokerage_type_list_controller');
$page->run();

$this->assing('main_list', $main_list);

?>
