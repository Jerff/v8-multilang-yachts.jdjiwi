<?php

/*$sql = cmfRegister::getSql();                                        //WHERE image IS NULL LIMIT 0, 30
$res = $sql->placeholder("SELECT id, image_main, image_small FROM ?t ", db_foto)
            ->fetchAssocAll('id');
foreach($res as $k=>$row) {
    //
//    $main1 = 'http://nsk-yachts.com.ua/upload/'. $row['image_main'];
//    $file1 = substr($row['image_main'], 0, 1) .'/'. substr($row['image_main'], 1, 1) .'/'. $row['image_main'];
//    dirCreate($file1);

//    $main2 = 'http://nsk-yachts.com.ua/upload/'. $row['image_small'];
//    $file2 = 'small/'. substr($row['image_small'], 0, 1) .'/'. substr($row['image_small'], 1, 1) .'/'. $row['image_small'];
//    dirCreate($file2);


//    file_put_contents(cmfWWW . cmfPathFoto . $file1, file_get_contents($main1));
//    file_put_contents(cmfWWW . cmfPathFoto . $file2, file_get_contents($main2));
    $file1 = $row['image_main'];
    $file2 = $row['image_small'];

    $new1 = mb_strtolower($file1);
    $new2 = mb_strtolower($file2);
    if($file1==$new1 and $file2==$new2) continue;
    cmfFile::rename($file1, $new1);
    cmfFile::rename($file2, $new2);

    $image = array('image'=>array('image_main'=>$new1, 'image_small'=>$new2));

    $send = array();
    $send['image'] = serialize($image);
    $send['image_main'] = $new1;
    $send['image_small'] = $new2;
    $sql->add(db_foto, $send, $k);
}
function dirCreate($file) {
    $dir = dirname(cmfWWW . cmfPathFoto . $file);
    if(!is_dir($dir)) {
       cmfDir::mkdir($dir);
    }
}*/

/*
UPDATE `s03_foto` SET `visible`='no';
*/
/*$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, image_main, image_small FROM ?t WHERE visible='no' LIMIT 0, 1500", db_foto)
            ->fetchAssocAll('id');
foreach($res as $k=>$row) {
    $main = cmfWWW . cmfPathFoto . $row['image_main'];
    $small = cmfWWW . cmfPathFoto . $row['image_small'];

    cmfFile::unlink($small);
	cmfFile::copy($main, $small);
	cmfImage::resize($small, fotoSmallWidth, fotoSmallHeight);
    $sql->add(db_foto, array('visible'=>'yes'), $k);
}
*/

$page = new cmfAdminController();
$main_edit = $page->load('foto_edit_controller');
$this->assing('id', $main_edit->getId());

$main_list = $page->load('foto_list_controller');
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
