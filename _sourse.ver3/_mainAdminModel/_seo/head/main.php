<?php


$page = new cmfAdminController();
$main_list = $page->load('_seo_head_controller');
$page->run();

//$sql = cmfRegister::getSql();
//$url = $sql->placeholder("SELECT `new` FROM ?t WHERE `url`=?", db_seo_head, $_SERVER['REQUEST_URI'])
//            ->fetchRow(0);
//pre($url);
$this->assing('main_list', $main_list);

?>
