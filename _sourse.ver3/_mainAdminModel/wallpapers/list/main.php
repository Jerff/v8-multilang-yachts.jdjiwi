<?php

/*$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, image_main, image_small FROM ?t WHERE image IS NULL LIMIT 0, 15", db_wallpapers)
            ->fetchAssocAll('id');
foreach($res as $k=>$row) {
    //
    $main1 = 'http://nsk-yachts.com.ua/upload/'. $row['image_main'];
    $file1 = substr($row['image_main'], 0, 1) .'/'. substr($row['image_main'], 1, 1) .'/'. $row['image_main'];
    dirCreate($file1);

    $main2 = 'http://nsk-yachts.com.ua/upload/'. $row['image_small'];
    $file2 = 'small/'. substr($row['image_small'], 0, 1) .'/'. substr($row['image_small'], 1, 1) .'/'. $row['image_small'];
    dirCreate($file2);


    $image = array('image'=>array('image_main'=>$file1, 'image_small'=>$file2));

    file_put_contents(cmfWWW . cmfPathWallpapers . $file1, file_get_contents($main1));
    file_put_contents(cmfWWW . cmfPathWallpapers . $file2, file_get_contents($main2));

    $send = array();
    $send['image'] = serialize($image);
    $send['image_main'] = $file1;
    $send['image_small'] = $file2;
    $sql->add(db_wallpapers, $send, $k);
}
*/
/*
UPDATE `s03_wallpapers` SET `visible`='no';
*/
/*$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, image_main, image_small FROM ?t WHERE visible='no' LIMIT 0, 15", db_wallpapers)
            ->fetchAssocAll('id');
foreach($res as $k=>$row) {
    $main = cmfWWW . cmfPathWallpapers . $row['image_main'];
    $small = cmfWWW . cmfPathWallpapers . $row['image_small'];

    cmfFile::unlink($small);
	cmfFile::copy($main, $small);
	cmfImage::resize($small, wallpapersSmallWidth, wallpapersSmallHeight);
	pre($small);
    $sql->add(db_wallpapers, array('visible'=>'yes'), $k);
}*/


$page = new cmfAdminController();
$main_edit = $page->load('wallpapers_edit_controller');
$this->assing('id', $main_edit->getId());

$main_list = $page->load('wallpapers_list_controller');
$page->run();


$this->assing('main_list', $main_list);
$this->assing('limitUrl', $main_list->getLimitUrl());
$this->assing('linkPage', $main_list->getLinkPage());

$this->assing('main_edit', $main_edit);
list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

list($langForm, $langData) = $main_edit->current()->lang;
$this->assing('langForm', $langForm);
$this->assing('langData', $langData);

?>