<?php

$page = new cmfAdminController();

$main_list = $page->load('_mail_templates_list_controller');
$this->assing('filterSection', $main_list->filterSection());
$page->run();


$this->assing('main_list', $main_list);


?>
