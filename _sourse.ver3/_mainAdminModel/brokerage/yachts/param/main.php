<?php

/*
UPDATE `s03_brokerage_yachts` SET `visible`='no';
*/
/*$sql = cmfRegister::getSql();
$_view = $sql->placeholder("SELECT id, `type` FROM ?t", db_param)
                   ->fetchRowAll(0, 1);
pre($_view);
$res = $sql->placeholder("SELECT id, param FROM ?t WHERE visible='no'", db_brokerage_yachts)
            ->fetchAssocAll();
foreach($res as $row) {
    foreach(cmfString::unserialize($row['param']) as $k=>$v) {
        switch(get($_view, $k)) {
            case 'checkbox':

                break;

            case 'radio':
            case 'select':
                $sql->replace(db_param_select, array('group'=>'brokerage', 'id'=>$row['id'], 'param'=>$k, 'value'=>$v));
                break;
        }
    }
    $sql->add(db_brokerage_yachts, array('visible'=>'yes'), $row['id']);
}
die();*/


if($yachts = cmfAdminMenu::getSubMenuId()) {
    cmfAdminMenu::setUserMenu('yachts');
    if(!cmfModulLoad('brokerage_yachts_edit_db')->getDataId($yachts)) {
        return cmfAdminNotRecord;
    }
    $this->assing('menu', cmfModulLoad('brokerage_yachts_edit_controller')->filterMenuAll($yachts));
} else {
    return cmfAdminNotRecord;
}

$page = new cmfAdminController();
$main_edit = $page->load('brokerage_yachts_param_controller');
$main_edit->setId(cmfAdminMenu::getSubMenuId());
$page->run();
if($main_edit->notId()) return cmfAdminNotRecord;



$this->assing('main_edit', $main_edit);
list($form, $data) = $main_edit->current()->main;
$this->assing('form', $form);
$this->assing('data', $data);

?>