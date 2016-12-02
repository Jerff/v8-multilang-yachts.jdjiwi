<?php

$arendaUri = cmfPages::getParam(1);
if(!$arendaUri) return 404;
$sql = cmfRegister::getSql();
$row = $sql->placeholder("SELECT id, page FROM ?t WHERE url=?", db_content_url, $arendaUri)
                            ->fetchRow();
if(!$row) return 404;
list($arendaId, $page) = $row;
if($page!=='/arenda/') return 404;
$arenda = $sql->placeholder("SELECT id, path, isUri, name, type, yachtsListUrl, title2, keywords2, description2 FROM ?t WHERE id=? AND isVisible='yes'", db_arenda, $arendaId)
            ->fetchAssoc();
if(!$arenda) return 404;
if($arenda['type']!='yachts' and $arenda['type']!='none') return 404;



cmfRedirectSeo(cmfGetUrl('/arenda/', array($arenda['isUri'])));
die();
cmfMenu::setHeader($arenda['name']);
cmfMenu::setRequest('arenda');
$this->assing('arenda', $arenda);

cmfSeo::set('title', $arenda['title2']);
cmfSeo::set('keywords', $arenda['keywords2']);
cmfSeo::set('description', $arenda['description2']);


$list = array($arendaId);
$isUkraine = $arenda['id']==3;
if(!empty($arenda['path']) and !$isUkraine) {
    $res = $sql->placeholder("SELECT id, name, isUri FROM ?t WHERE id ?@ AND isVisible='yes' ORDER BY level", db_arenda, cmfString::pathToArray($arenda['path']))
                ->fetchAssocAll();
    $menu = array();
    foreach($res as $row) {
        $list[] = $row['id'];
        if($row['id']==3) {
	        $menu = array();
	        $isUkraine = true;
	    }
	    $menu[$row['name']] = cmfGetUrl('/arenda/', array($row['isUri']));
	}
}

if($isUkraine) {
   cmfGlobal::set('isUkraine', true);
   $header = $sql->placeholder("SELECT header FROM ?t WHERE id='arenda/info' LIMIT 0,1", db_main)
                       ->fetchRow(0);
    cmfMenu::add($header, cmfGetUrl('/arenda/info/'));
} else {
    cmfGlobal::set('isCharter', true);
    $header = $sql->placeholder("SELECT header FROM ?t WHERE id='charter' LIMIT 0,1", db_main)
                       ->fetchRow(0);
    cmfMenu::add($header, cmfGetUrl('/charter/'));
}
cmfGlobal::set('arendaId', $arenda['id']);
if(isset($menu))
	foreach($menu as $k=>$v) {
	    cmfMenu::add($k, $v);
	}
cmfMenu::add($arenda['name'], cmfGetUrl('/arenda/', array($arenda['isUri'])));
cmfMenu::add('Все '. $arenda['yachtsListUrl'], cmfGetUrl('/arenda/all/', array($arendaUri)));


    cmfLoad('catalog/function');
    $_type = $sql->placeholder("SELECT id, name, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_arenda_type)
                    ->fetchAssocAll('id');

    $paramValue1 = cmfParam::getParamId($paramId1 = cmfConfig::get('site', 'arendaParam1'));
    $paramValue2 = cmfParam::getParamId($paramId2 = cmfConfig::get('site', 'arendaParam2'));
    $res = $sql->placeholder("SELECT type, id, uri, name, image_small, image_title, param, priceHour, priceDay, priceWeek, currency FROM ?t WHERE id IN(SELECT yachts FROM ?t WHERE arenda IN(SELECT id FROM ?t WHERE (id=? OR path LIKE '%[?i]%') AND isVisible='yes') OR arenda ?@) AND type ?@ AND visible='yes' ORDER BY pos, name", db_arenda_yachts, db_arenda_list, db_arenda, $arendaId, $arendaId, $list, array_keys($_type))
    								->fetchAssocAll();
    $_yachts = array();
    foreach($res as $row) {
        $_yachts[$row['type']][$row['id']] = array('name'=>$row['name'],
                                                   'param1'=>cmfParam::selectParam($row['param'], $paramId1, $paramValue1),
                                                   'param2'=>cmfParam::selectParam($row['param'], $paramId2, $paramValue2, false),
                                                   'image'=>cmfBaseImg . cmfPathArendaYachts . $row['image_small'],
                                            	   'title'=>htmlspecialchars($row['image_title']),
                                            	   'priceHour'=>cmfPrice::view2($row['priceHour'], $row['currency']),
                                            	   'priceDay'=>cmfPrice::view2($row['priceDay'], $row['currency']),
                                            	   'priceWeek'=>cmfPrice::view2($row['priceWeek'], $row['currency']),
                                            	   'url'=>cmfGetUrl('/arenda/yachts/', array($_type[$row['type']]['uri'], $row['uri'])));
    }
    foreach($_type as $id=>$row) {
        if(isset($_yachts[$id])) {
            $_yachts[$id] =  array_chunk($_yachts[$id], 3);
        } else {
            unset($_type[$id]);
        }
    }
    reset($_type);
    $_type[key($_type)]['sel'] = true;
    $this->assing2('_type', $_type);
    $this->assing('_yachts', $_yachts);

?>