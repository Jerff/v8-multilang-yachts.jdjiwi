<?php

$infoId = cmfGlobal::get('$infoId');
$arendaId = cmfGlobal::get('$param1');
if (!$infoId or !$arendaId)
    return 404;

cmfLoad('catalog/function');
if ($info = cmfCache::getParam('arenda', array($infoId, $arendaId))) {
    list($info, $mMenu, $request, $headerId, $mPath, $arenda, $isUkraine, $sYachts) = $info;

} else {

    if (!$info = cmfContent::info($infoId))
        return 404;
    list($info, $request, $headerId, $mPath) = $info;
    $mMenu = cmfContent::initSubMenu($info, $infoId, $arendaId);


    $sql = cmfRegister::getSql();
    $arenda = $sql->placeholder("SELECT id, path, phone, type, isUri FROM ?t WHERE id=? AND visible='yes'", db_arenda, $arendaId)
            ->fetchAssoc();
    if (!$arenda)
        return 404;
    $list = $sql->placeholder("SELECT lang, name, content, title, keywords, description FROM ?t WHERE id=?", db_arenda_lang, $arendaId)
            ->fetchAssocAll('lang');
    cmfLang::data($arenda, $list);



    $mList = array($arendaId);
    $isUkraine = $arenda['id'] == arendaMenu;
    if(strpos($arenda['path'], "[3]")!==false) {
        $arenda['path'] = substr($arenda['path'], strpos($arenda['path'], "[3]")+3);
    }
    if (!empty($arenda['path']) and !$isUkraine) {
        $res = $sql->placeholder("SELECT id, name, isUri FROM ?t WHERE id ?@ AND isVisible='yes' ORDER BY level", db_arenda, cmfString::pathToArray($arenda['path']))
                ->fetchAssocAll();
        $menu = array();
        foreach ($res as $row) {
            $mList[] = $row['id'];
            $mPath[cmfGetUrl('/arenda/', array($row['isUri']))] = $row['name'];
        }
    }
    $mPath[] = $arenda['name'];


    $res = $sql->placeholder("SELECT id, image, name, link FROM ?t WHERE page='arenda/edit' AND parent=? AND visible='yes' ORDER BY main, pos DESC", db_main_slider, $arendaId)
            ->fetchAssocAll('id');
    $mSlider = array();
    if ($res) {
        $list = $sql->placeholder("SELECT id, lang, name, notice FROM ?t WHERE id ?@ AND page='arenda/edit' AND parent=?", db_main_slider_lang, array_keys($res), $arendaId)
                ->fetchAssocAll('id', 'lang');
        foreach ($res as $id => $row) {

            cmfLang::data($row, get($list, $id));
			if(empty($title)) $title = $i;
            $mSlider[$row['id']] = array(
                'title' => empty($row['notice']) ? htmlspecialchars($row['name']) : '#notice' . $row['id'],
                'alt' => htmlspecialchars($row['name']),
                'link' => $row['link'],
                'notice' => $row['notice'],
                //'image' => cmfPathMain . $row['image'],
				//'image' => cmfImage::preview(cmfBaseImg . cmfPathShipyards . $row['image'], 250, 130,$row['image'],$i++),
				'image' => cmfImage::preview(cmfPathMain . $row['image'], 592, 267,$row['image'],'a-s', $row['id']),
            );
        }
    }
    cmfContent::replace($this, $arenda['content'], $mSlider, true);
    cmfGlobal::set('arendaIdList', $mList);
    $sYachts = $this->run('/arendaYachts/');
    if(!cmfGlobal::get('isYachts')) {
        $sYachts = false;
    }

    cmfCache::setParam('arenda', array($infoId, $arendaId), array($info, $mMenu, $request, $headerId, $mPath, $arenda, $isUkraine, $sYachts), 'photo');
}
//$this->assing2('saleUrl', cmfGetUrl('/request/sale/') . '?type=brokerage&id=' . $yachts['id']);
foreach ($mPath as $key => $value) {
    if (is_string($key)) {
        cmfMenu::add($value, $key);
    } else {
        cmfMenu::add($value);
    }
}
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu);

cmfGlobal::set('isUkraine', $isUkraine);
cmfGlobal::set('arendaId', $arendaId);
cmfGlobal::set('arendaPhone', $arenda['phone']);

cmfMenu::setH1($arenda['name']);
cmfSeo::set('title', $arenda['title']);
cmfSeo::set('keywords', $arenda['keywords']);
cmfSeo::set('description', $arenda['description']);


$this->assing('arenda', $arenda);
if ($sYachts) {
    cmfGlobal::set('body', 'rent');
    $this->setTeplates('main.yachts.php');
    cmfGlobal::set('header-content', $sYachts);
} else {
    cmfGlobal::set('body', 'info');
}

return 1;
pre($infoId, $arendaId);
exit;




if ($arenda['type'] === 'yachts' or $arenda['type'] === 'none') {
    cmfLoad('catalog/function');
    $_type = $sql->placeholder("SELECT id, name, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_arenda_type)
            ->fetchAssocAll('id');

    $paramValue1 = cmfParam::getParamId($paramId1 = cmfConfig::get('site', 'arendaParam1'));
    $paramValue2 = cmfParam::getParamId($paramId2 = cmfConfig::get('site', 'arendaParam2'));
    $res = $sql->placeholder("SELECT type, id, uri, name, image_small, image_title, param, priceHour, priceLightDay, priceDay, priceWeek, currency FROM ?t WHERE id IN(SELECT yachts FROM ?t WHERE arenda IN(SELECT id FROM ?t WHERE (id=? OR path LIKE '%[?i]%') AND isVisible='yes') OR arenda ?@) AND type ?@ AND visible='yes' ORDER BY pos, name", db_arenda_yachts, db_arenda_list, db_arenda, $arendaId, $arendaId, $list, array_keys($_type))
            ->fetchAssocAll();
    $_yachts = array();
    $arendaUri = $arenda['isUri'];
	$i = 0;
    foreach ($res as $row) {
        $_yachts[$row['type']][$row['id']] = array('name' => $row['name'],
            'param1' => cmfParam::selectParam($row['param'], $paramId1, $paramValue1),
            'param2' => cmfParam::selectParam($row['param'], $paramId2, $paramValue2, false),
            //'image' => cmfBaseImg . cmfPathArendaYachts . $row['image_small'],
			'image' => cmfImage::preview(cmfBaseImg . cmfPathShipyards . $row['image'], 250, 130,$row['name'],$i++),
            'title' => htmlspecialchars($row['image_title']),
            'priceHour' => cmfPrice::view2($row['priceHour'], $row['currency']),
            'priceLightDay' => cmfPrice::view2($row['priceLightDay'], $row['currency']),
            'priceDay' => cmfPrice::view2($row['priceDay'], $row['currency']),
            'priceWeek' => cmfPrice::view2($row['priceWeek'], $row['currency']),
            'url' => cmfGetUrl('/arenda/yachts/', array($_type[$row['type']]['uri'], $row['uri'])));
    }


    foreach ($_type as $id => $row) {
        if (isset($_yachts[$id])) {
            $_yachts[$id] = array_chunk($_yachts[$id], 3);
        } else {
            unset($_type[$id]);
        }
    }
    reset($_type);
    $_type[key($_type)]['sel'] = true;
    $this->assing2('_type', $_type);
    if ($_yachts)
        $this->assing('_yachts', $_yachts);
    list($phone1, $phone2) = explode(": ", cmfConfig::get('site', 'arendaPhone'));
    $this->assing2('phone1', $phone1);
    $this->assing2('phone2', $phone2);
    $this->assing2('allUrl', cmfGetUrl('/arenda/all/', array($arendaUri)));
}
?>