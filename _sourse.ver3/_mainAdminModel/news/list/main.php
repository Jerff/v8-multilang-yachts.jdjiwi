<?php

/*$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, image FROM ?t WHERE image IS NOT NULL", db_article)
            ->fetchRowAll(0, 1);
foreach($res as $k=>$file) if($file) {
    $upload = 'http://nsk-yachts.com.ua/upload/'. $file;
    $file = cmfWWW . cmfPathArticle . $file;
    pre($file, $upload);
    file_put_contents($file, file_get_contents($upload));
    //$send = array();
    //$send['upload'] = preg_replace('~(.+[\\\/])([^.]+)(.*)(\..+)~', '$2$4', $file);
    //$sql->add(db_materials, $send, $k);
}
die();*/

$page = new cmfAdminController();

$main_list = $page->load('news_list_controller');
$page->run();


$this->assing('main_list', $main_list);
$this->assing('limitUrl', $main_list->getLimitUrl());
$this->assing('linkPage', $main_list->getLinkPage());

?>
