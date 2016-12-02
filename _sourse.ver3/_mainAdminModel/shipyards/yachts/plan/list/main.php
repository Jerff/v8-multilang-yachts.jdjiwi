<?php

/*//REPLACE `s03_shipyards_yachts_plan` SELECT `id`, `id_yacht_plan`, 0, 'no', 'no', '', '', name, thumb FROM 2011_01_old.yacht_plan_foto
$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, yachts, image_main, image_small FROM ?t WHERE visible='no' ORDER BY id LIMIT 0, 5000", db_shipyards_yachts_plan)
            ->fetchAssocAll('id');
$path = cmfWWW . cmfPathShipyardsYachtsPlan;
foreach($res as $k=>$row) {

    $main1 = 'http://nsk-yachts.com.ua/upload/'. $row['image_main'];
    $file1 = substr($row['image_main'], -7, 1) .'/'. substr($row['image_main'], -6, 1) .'/'. substr($row['image_main'], -5, 1) .'/'. $row['image_main'];
    cmfDir::mkdir(dirname($path . $file1));
    file_put_contents($path . $file1, file_get_contents($main1));
    cmfImage::resize($path . $file1, yachtsPlanWidth, yachtsPlanHeight);

    $file2 = 'small/'. $file1;
    cmfDir::mkdir(dirname($path . $file2));
    cmfFile::copy($path . $file1, $path . $file2);
    cmfImage::resize($path . $file2, yachtsPlanSmallWidth, yachtsPlanSmallHeight);


    $image = array('image'=>array('image_main'=>$file1, 'image_small'=>$file2));
    $send = array();
    $send['pos'] = 1+$sql->placeholder("SELECT max(pos) FROM ?t WHERE yachts=?", db_shipyards_yachts_plan, $row['yachts'])
                        ->fetchRow(0);
    $send['visible'] = 'yes';
    $send['image'] = serialize($image);
    $send['image_main'] = $file1;
    $send['image_small'] = $file2;
    $sql->add(db_shipyards_yachts_plan, $send, $k);
    //sleep(1);
}*/

/*//UPDATE `s03_shipyards_yachts_plan` SET `pos`='0'
$sql = cmfRegister::getSql();
//$sql->placeholder("UPDATE ?t SET `pos`='0'", db_shipyards_yachts_plan);
$res = $sql->placeholder("SELECT id, yachts FROM ?t WHERE pos='0' ORDER BY id DESC LIMIT 0, 100000", db_shipyards_yachts_plan)
            ->fetchAssocAll('id');
foreach($res as $k=>$row) {
    $send = array();
    $send['pos'] = 1+$sql->placeholder("SELECT max(pos) FROM ?t WHERE yachts=? ORDER BY id ", db_shipyards_yachts_plan, $row['yachts'])
                        ->fetchRow(0);
    $sql->add(db_shipyards_yachts_plan, $send, $k);
}*/

/*
UPDATE  2011_01.s03_shipyards_yachts_plan a SET a.`visible`='no',
a.`image_main`=(SELECT name FROM 2011_01_old. yacht_plan_foto o WHERE o.id=a.id),
a.`image_small`=(SELECT thumb FROM 2011_01_old. yacht_plan_foto o WHERE o.id=a.id)
*//*
$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, image_main, image_small FROM ?t WHERE visible='no'", db_shipyards_yachts_plan)
            ->fetchAssocAll('id');
$path = cmfWWW .cmfPathShipyardsYachtsPlan;
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
    $sql->add(db_shipyards_yachts_plan, $send, $k);
}
die();*/


if($yachts = cmfAdminMenu::getSubMenuId()) {
    cmfAdminMenu::setUserMenu('yachts');
    if(!cmfModulLoad('shipyards_yachts_edit_db')->getDataId($yachts)) {
        return cmfAdminNotRecord;
    }
    $this->assing('menu', cmfModulLoad('shipyards_yachts_edit_controller')->filterMenuAll($yachts));
} else {
    return cmfAdminNotRecord;
}


$page = new cmfAdminController();
$main_edit = $page->load('shipyards_yachts_plan_edit_controller');
$this->assing('id', $main_edit->getId());

$main_multi = $page->load('shipyards_yachts_plan_multi_controller');
$main_list = $page->load('shipyards_yachts_plan_list_controller');
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
