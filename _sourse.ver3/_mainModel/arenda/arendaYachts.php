<?php

$infoId = cmfGlobal::get('$infoId');
$arendaId = cmfGlobal::get('$param1');
$mList = cmfGlobal::get('arendaIdList');
cmfLoad('catalog/function');
if ($mType = cmfCache::getParam('arenda/yachts', array($infoId, $arendaId, $mList))) {
    list($mType, $mYachtsList, $mYachts) = $mType;
} else {

    $sql = cmfRegister::getSql();
    $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_arenda_type)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name, menu FROM ?t WHERE id ?@", db_arenda_type_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mType = array();
    $mType['all'] = array(
        'name' => word('Все яхты')
    );
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mType[$id] = array(
            'uri' => $row['uri'],
            'name' => $row['name']
        );
    }


    $paramValue1 = cmfParam::getParamId($paramId1 = cmfConfig::get('site', 'arendaParam1'));
    $paramValue2 = cmfParam::getParamId($paramId2 = cmfConfig::get('site', 'arendaParam3'));
    $paramValue3 = cmfParam::getParamId($paramId3 = cmfConfig::get('site', 'arendaParam2'));
    $res = $sql->placeholder("SELECT type, `update`, id, uri, image_small, param, priceHour, priceLightDay, priceDay, priceWeek, currency FROM ?t WHERE id IN(SELECT yachts FROM ?t WHERE arenda IN(SELECT id FROM ?t WHERE (id=? OR path LIKE '%[?i]%') AND isVisible='yes') OR arenda ?@) AND type ?@ AND visible='yes' ORDER BY pos, name", db_arenda_yachts, db_arenda_list, db_arenda, $arendaId, $arendaId, $mList, array_keys($mType))
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name, image_title FROM ?t WHERE id ?@", db_arenda_yachts_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mYachts = $mYachtsList = array();
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mYachtsList['all'][$id] = $id;
        $mYachtsList[$row['type']][$id] = $id;
        $mYachts[$id] = array(
            'name' => $row['name'],
            'param1' => cmfParam::selectParam($row['param'], $paramId1, $paramValue1),
            'param2' => cmfParam::selectParam($row['param'], $paramId2, $paramValue2, false),
            'param3' => cmfParam::selectParam($row['param'], $paramId3, $paramValue3, false),
            'image' => cmfImage::preview(cmfBaseOld . cmfPathArendaYachts . $row['image_small'], 250, 130, $row['uri'], 'a', $row['id'], $row['update']),
            'title' => htmlspecialchars($row['image_title']),
            'priceHour' => cmfPrice::view2($row['priceHour'], $row['currency']),
            'priceLightDay' => cmfPrice::view2($row['priceLightDay'], $row['currency']),
            'priceDay' => cmfPrice::view2($row['priceDay'], $row['currency']),
            'priceWeek' => cmfPrice::view2($row['priceWeek'], $row['currency']),
            'url' => cmfGetUrl('/arenda/yachts/', array($mType[$row['type']]['uri'], $row['uri']))
        );
        list(,,, $mYachts[$id]['width']) = getimagesize($mYachts[$id]['image']);
    }

    foreach ($mType as $id => $row) {
        if (!isset($mYachtsList[$id])) {
            unset($mType[$id]);
        }
    }
    reset($mType);
    $mType[key($mType)]['sel'] = true;
    cmfCache::setParam('arenda/yachts', array($infoId, $arendaId, $mList), array($mType, $mYachtsList, $mYachts), 'photo');
}

if ($mYachts) {
    cmfGlobal::set('isYachts', 1);
    $this->assing('mType', $mType);
    $this->assing('mYachtsList', $mYachtsList);
    $this->assing('mYachts', $mYachts);
}
?>