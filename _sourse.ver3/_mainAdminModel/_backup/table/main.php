<?php

$page = new cmfAdminController();

$main_edit = $page->load('_backup_table_controller');
$page->run();

$this->assing('main_edit', $main_edit);


?>