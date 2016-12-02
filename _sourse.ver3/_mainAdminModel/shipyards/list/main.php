<?php

//REPLACE `s03_shipyards` SELECT `id`, `data`, 'yes', `url`, `title_h1`,`text`, `pic_logo`,`name_logo`,`pic_land`,`name_land`,`title`,`keywords`,`description` FROM 2011_01_old.shipyard
/*$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, image, land FROM ?t WHERE visible='no' LIMIT 0, 15", db_shipyards)
            ->fetchAssocAll('id');
foreach($res as $k=>$row) {
    //
    $main1 = 'http://nsk-yachts.com.ua/upload/'. $row['image'];
    $file1 = substr($row['image'], 0, 1) .'/'. substr($row['image'], 1, 1) .'/'. $row['image'];
    cmfDir::mkdir(dirname(cmfWWW . cmfPathShipyards . $file1));;

    $main2 = 'http://nsk-yachts.com.ua/upload/'. $row['land'];
    $file2 = substr($row['land'], 0, 1) .'/'. substr($row['land'], 1, 1) .'/'. $row['land'];
    cmfDir::mkdir(dirname(cmfWWW . cmfPathShipyards . $file2));

    file_put_contents(cmfWWW . cmfPathShipyards . $file1, file_get_contents($main1));
    file_put_contents(cmfWWW . cmfPathShipyards . $file2, file_get_contents($main2));

    $send = array();
    $send['visible'] = 'yes';
    $send['image'] = $file1;
    $send['land'] = $file2;
    $sql->add(db_shipyards, $send, $k);
}*/

/*$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id FROM ?t ORDER BY name", db_shipyards)
            ->fetchAssocAll('id');
foreach($res as $k=>$row) {
    $send = array();
    $send['pos'] = 1+$sql->placeholder("SELECT max(pos) FROM ?t", db_shipyards)
                        ->fetchRow(0);
    $sql->add(db_shipyards, $send, $k);
}*/

$page = new cmfAdminController();
$main_list = $page->load('shipyards_list_controller');
$this->assing('filterLogo', $main_list->filterLogo());
$page->run();


$this->assing('main_list', $main_list);
$this->assing('limitUrl', $main_list->getLimitUrl());
$this->assing('linkPage', $main_list->getLinkPage());

?>
