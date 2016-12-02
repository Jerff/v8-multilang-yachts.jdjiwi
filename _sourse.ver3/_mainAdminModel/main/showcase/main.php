<?php

$page = new cmfAdminController();

$main_list = $page->load('main_showcase_controller');
$page->run();
if($main_list->notId()) return cmfAdminNotRecord;


$this->assing('main_list', $main_list);

?>