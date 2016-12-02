<?php

$page = new cmfAdminController();

$import = $page->load('_backup_site_import_controller');
$main_list = $page->load('_backup_site_list_controller');
$page->run();

$this->assing('fileList', $import->getFileList());
$this->assing('main_list', $main_list);
$this->assing('import', $import);

?>
