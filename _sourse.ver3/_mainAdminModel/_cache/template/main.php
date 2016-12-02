<?php

$page = new cmfAdminController();

$main_edit = $page->load('_cache_template_controller');
$page->run();

$this->assing('main_edit', $main_edit);


?>