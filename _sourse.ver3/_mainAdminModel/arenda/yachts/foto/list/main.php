<?php

//REPLACE `s03_arenda_yachts_foto` SELECT `id`, `id_arenda`, 0, 'no', 'no', '', '', name, thumb FROM 2011_01_old.arenda_foto
/*$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, yachts, image_main, image_small FROM ?t WHERE visible='no' ORDER BY id DESC LIMIT 0, 8000", db_arenda_yachts_foto)
            ->fetchAssocAll('id');
$path = cmfWWW . cmfPathArendaYachtsFoto;
foreach($res as $k=>$row) {

    $main1 = 'http://nsk-yachts.com.ua/upload/'. $row['image_main'];
    $file1 = substr($row['image_main'], 0, 1) .'/'. substr($row['image_main'], 2, 1) .'/'. $row['image_main'];
    cmfDir::mkdir(dirname($path . $file1));
    file_put_contents($path . $file1, file_get_contents($main1));
    cmfImage::resize($path . $file1, yachtsFotoWidth, yachtsFotoHeight);

    $file2 = 'small/'. $file1;
    cmfDir::mkdir(dirname($path . $file2));
    cmfFile::copy($path . $file1, $path . $file2);
    cmfImage::resize($path . $file2, yachtsFotoSmallWidth, yachtsFotoSmallHeight);


    $image = array('image'=>array('image_main'=>$file1, 'image_small'=>$file2));
    $send = array();
    $send['pos'] = 1+$sql->placeholder("SELECT max(pos) FROM ?t WHERE yachts=? ORDER BY id ", db_arenda_yachts_foto, $row['yachts'])
                        ->fetchRow(0);
    $send['visible'] = 'yes';
    $send['image'] = serialize($image);
    $send['image_main'] = $file1;
    $send['image_small'] = $file2;
    $sql->add(db_arenda_yachts_foto, $send, $k);
    //sleep(1);
}
*/


/*
UPDATE  2011_01.s03_arenda_yachts_foto a SET a.`visible`='no',
a.`image_main`=(SELECT name FROM 2011_01_old. arenda_foto o WHERE o.id=a.id),
a.`image_small`=(SELECT thumb FROM 2011_01_old. arenda_foto o WHERE o.id=a.id)
*//*
$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, image_main, image_small FROM ?t WHERE visible='no'", db_arenda_yachts_foto)
            ->fetchAssocAll('id');
$path = cmfWWW . cmfPathArendaYachtsFoto;
foreach($res as $k=>$row) {

    $main1 = 'http://nsk-yachts.com.ua/upload/'. $row['image_main'];
    $file1 = $path . $row['image_main'];
    $file2 = $path . $row['image_small'];
    file_put_contents($file2, file_get_contents($main1));
    cmfImage::resize($file2, yachtsFotoSmallWidth, yachtsFotoSmallHeight);

    $image = array('image'=>array('image_main'=>$row['image_main'], 'image_small'=>$row['image_small']));
    $send = array();
    $send['visible'] = 'yes';
    $send['image'] = serialize($image);
    $sql->add(db_arenda_yachts_foto, $send, $k);
}
die();*/

/*$path = cmfWWW .'upload';
chmod_R($path, 0777);
function chmod_R($path, $perm) {
    pre($path);
    $handle = opendir($path);
    while ( false !== ($file = readdir($handle)) ) {
        if(strpos($file, '.')===false) {
            if(is_dir($path . "/" . $file)) {
                chmod($path . "/" . $file, $perm);
                chmod_R($path . "/" . $file, $perm);
            }
        }
    }
    closedir($handle);
}
die();*/




if($yachts = cmfAdminMenu::getSubMenuId()) {
    cmfAdminMenu::setUserMenu('yachts');
    if(!cmfModulLoad('arenda_yachts_edit_db')->getDataId($yachts)) {
        return cmfAdminNotRecord;
    }
    $this->assing('menu', cmfModulLoad('arenda_yachts_edit_controller')->filterMenuAll($yachts));
} else {
    return cmfAdminNotRecord;
}

$page = new cmfAdminController();
$main_edit = $page->load('arenda_yachts_foto_edit_controller');
$this->assing('id', $main_edit->getId());

$main_multi = $page->load('arenda_yachts_foto_multi_controller');
$main_list = $page->load('arenda_yachts_foto_list_controller');
$page->run();


$this->assing('isMultiImage', cmfCommand::get('isMultiUplod'));
$this->assing('main_multi', $main_multi);

$this->assing('main_list', $main_list);
$this->assing('limitUrl', $main_list->getLimitUrl());
$this->assing('linkPage', $main_list->getLinkPage());

$this->assing('main_edit', $main_edit);
list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

?>
