<?php

if(cmfMenu::isH1()) {
    $this->assing2('h1', cmfMenu::h1());
}
$this->assing2('mMenu', cmfMenu::getRMenu());
$this->assing2('mBMenu', cmfMenu::getBMenu());
$this->assing2('boardAdd', cmfGetUrl('/board/add/'));
if(cmfGlobal::is('header-content')) {
    $this->assing2('content', cmfGlobal::get('header-content'));
}
return 1;
$arendaId = cmfGlobal::get('arendaId');
$menuId = cmfGlobal::get('isUkraine') .'-'. cmfGlobal::get('isCharter') .'-'. cmfGlobal::get('arendaId');
if($parentId = cmfCache::get('info.header1'. $menuId)) {
    list($parentId, $charterName, $charterName, $ukraineName, $ukraineUrl, $menu, $_yachts) = $parentId;
} else {

    $charterName = $ukraineName = $ukraineUrl = '';
    $sql = cmfRegister::getSql();
    if(cmfGlobal::get('isUkraine')) {
        $parentId = 3;
        $res = $sql->placeholder("SELECT id, parent, name, isUri FROM ?t WHERE id=? OR parent=? OR path LIKE '%[?i]%' AND visible='yes' AND level<'4' ORDER BY pos", db_arenda, $parentId, $parentId, $parentId)
                                    ->fetchAssocAll('id');
        $charterName = $sql->placeholder("SELECT header FROM ?t WHERE id='charter'", db_main)
                             ->fetchRow(0);
        $ukraineName = $res[$parentId]['name'];
        $ukraineUrl = cmfGetUrl('/arenda/', array($res[$parentId]['isUri']));


    } elseif(cmfGlobal::get('isCharter')) {
        $parentId = 0;
        $res = $sql->placeholder("SELECT id, parent, name, isUri FROM ?t WHERE visible='yes' AND level<'2' ORDER BY pos", db_arenda)
                                    ->fetchAssocAll('id');
    }

    $menu = array();
    if(isset($res)) {
        foreach($res as $k=>$row) {
            $menu[$row['parent']][$k] = array('name'=>$row['name'],
                                                      'sel'=>$arendaId==$k,
                                                      'url'=>cmfGetUrl('/arenda/', array($row['isUri'])));
        }
    }

    cmfLoad('catalog/function');;
    $paramValue = cmfParam::getParamId($paramId = cmfConfig::get('site', 'brokerageParam'));
    $lengtValue = cmfParam::getParamId($lengthId = cmfConfig::get('site', 'brokerageShowcaseParam'));
    $res = $sql->placeholder("SELECT y.`type`, y.id, y.uri, y.name, y.image_best, y.image_title, y.param, y.price, y.currency, y.shipyardsName AS sName, t.uri AS tUri FROM ?t y LEFT JOIN ?t t ON(y.type=t.id) WHERE t.visible='yes' AND y.menu='yes' AND y.visible='yes' ORDER BY y.name LIMIT 0, 4", db_brokerage_yachts, db_brokerage_type)
                                    ->fetchAssocAll();
    $_yachts = array();
    foreach($res as $row) {
        $_yachts[$row['id']] = array('shipyards'=>$row['sName'],
                                     'name'=>$row['name'],
                                     'param'=>cmfParam::selectParam($row['param'], $paramId, $paramValue, false),
                                     'lengt'=>cmfParam::selectParam($row['param'], $lengthId, $lengtValue),
                                     'image'=>cmfBaseImg . cmfPathBrokerageYachts . $row['image_best'],
                                     'title'=>htmlspecialchars($row['image_title']),
                                     'price'=>cmfPrice::view($row['price'], $row['currency']),
                                     'url'=>cmfGetUrl('/brokerage/yachts/', array($row['tUri'], $row['uri'])));
    }

	cmfCache::set('info.header1'. $menuId, array($parentId, $charterName, $charterName, $ukraineName, $ukraineUrl, $menu, $_yachts), 'menu');
}

if(cmfGlobal::get('isUkraine')) {
    $this->assing('parentId', $parentId);
    $this->assing2('ukraineSel', $parentId==$arendaId);
    $this->assing('ukraineName', $ukraineName);
    $this->assing2('ukraineUrl', $ukraineUrl);

    $this->assing2('charterName', $charterName);
    $this->assing2('charterUrl', cmfGetUrl('/charter/'));

}
if($menu) {
    $this->assing2('submenu', $menu);
}

$this->assing2('_yachts', $_yachts);
$this->assing2('brokerageUrl', cmfGetUrl('/brokerage/'));

/*
$arendaId = cmfGlobal::get('arendaId');
if($arendaId) {
    $res = $sql->placeholder("SELECT id, parent, name, isUri FROM ?t WHERE parent=? OR path LIKE '%[?i]%' AND visible='yes' ORDER BY level, pos", db_arenda, $arendaId, $arendaId)
				->fetchAssocAll();
	$menu = array();
	foreach($res as $row) {
	    $menu[$row['parent']][$row['id']] = array('name'=>$row['name'],
	                                              'url'=>cmfGetUrl('/arenda/', array($row['isUri'])));
	}
	$this->assing2('menu', $menu);
}*/

?>