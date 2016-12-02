<?php
if ($mType = cmfCache::get('shipyards/list')) {
    list($mType, $mShipyards) = $mType;
} else {

    $sql = cmfRegister::getSql();
    $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_shipyards_type)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name, menu FROM ?t WHERE id ?@", db_shipyards_type_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mType = array();
    $mType['all'] = array(
        'name' => word('Все верфи'),
        'sel' => true,
        'url' => cmfGetUrl('/shipyards/')
    );
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mType[$id] = array(
            'name' => $row['name'],
            'menu' => $row['menu'],
            'url' => cmfGetUrl('/shipyards/type/', array($row['uri']))
        );
    }

    $res = $sql->placeholder("SELECT id, uri, image FROM ?t WHERE visible='yes' ORDER BY `main`, pos", db_shipyards)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name, image_title FROM ?t WHERE id ?@", db_shipyards_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mShipyards = array();
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mShipyards[$id] = array(
            'title' => htmlspecialchars($row['image_title']),
            'name' => $row['name'],
            'image' => cmfImage::preview(cmfBaseImg . cmfPathShipyards . $row['image'], 99, 45,$row['name'], 's-l', $id),
            'url' => cmfGetUrl('/shipyards/one/', array($row['uri']))
        );
        list(,,, $mShipyards[$id]['width']) = getimagesize($mShipyards[$id]['image']);
    }
	//cl($mShipyards,1);

    cmfCache::set('shipyards/list', array($mType, $mShipyards), 'shipyards');
}

$this->assing('mType', $mType);
$this->assing('mShipyards', $mShipyards);
cmfGlobal::set('body', 'rent');
$this->setTeplates('main.yachts.php');
?>