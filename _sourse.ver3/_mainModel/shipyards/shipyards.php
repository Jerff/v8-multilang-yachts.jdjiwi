<?php



cmfLoad('catalog/function');
$infoId = cmfGlobal::get('$infoId');
$shipyardsId = cmfGlobal::get('$param1');
if (!$infoId or !$shipyardsId)
    return 404;

if ($info = cmfCache::getParam('shipyards', array($infoId, $shipyardsId))) {
    list($info, $mMenu, $request, $headerId, $mPath, $shipyards, $mYachts) = $info;
} else {

    if (!$info = cmfContent::info($infoId))
        return 404;
    list($info, $request, $headerId, $mPath) = $info;
    $mMenu = cmfContent::initSubMenu($info, $infoId);

    $sql = cmfRegister::getSql();
    $shipyards = $sql->placeholder("SELECT id, uri, image, land FROM ?t WHERE id=? AND visible='yes'", db_shipyards, $shipyardsId)
            ->fetchAssoc();
    if (!$shipyards)
        return 404;
    $list = $sql->placeholder("SELECT lang, name, content, image_title, land_title, title, keywords, description  FROM ?t WHERE id=?", db_shipyards_lang, $shipyardsId)
            ->fetchAssocAll('lang');
    cmfLang::data($shipyards, $list);
    $mPath[] = $shipyards['name'];
    if ($shipyards['image']) {
        $shipyards['image'] = cmfBaseImg . cmfPathShipyards . $shipyards['image'];
		$shipyards['image'] =  cmfImage::preview($shipyards['image'], 200, 90,$shipyards['name'], 0);
        list(,,, $shipyards['image_width']) = getimagesize($shipyards['image']);
        $shipyards['image_title'] = htmlspecialchars($shipyards['image_title']);
    }
    if ($shipyards['land']) {
        $shipyards['land'] = cmfBaseImg . cmfPathShipyards . $shipyards['land'];
		$shipyards['land'] =  cmfImage::preview($shipyards['land'], 200, 90,$shipyards['land_title'], 0);

        list(,,, $shipyards['lang_width']) = getimagesize($shipyards['land']);
        $shipyards['land_title2'] = htmlspecialchars($shipyards['land_title']);
    }


    $paramId = cmfConfig::get('site', 'shipyardsParam');
    $paramValue = cmfParam::getParamId($paramId);
    $res = $sql->placeholder("SELECT id, `update`, uri, image_small, param, price, currency FROM ?t WHERE shipyards=? AND visible='yes' ORDER BY name", db_shipyards_yachts, $shipyardsId)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name, image_title FROM ?t y WHERE y.id ?@", db_shipyards_yachts_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mYachts = array();
	$i = 0;
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mYachts[$id] = array(
            'name' => $row['name'],
            'param' => cmfParam::selectParam($row['param'], $paramId, $paramValue),
            'image' => cmfImage::preview(cmfBaseOld . cmfPathShipyardsYachts . $row['image_small'], 250, 130,$row['name'], 's', $row['id'], $row['update']),
            'title' => htmlspecialchars($row['image_title']),
            'price' => cmfPrice::view($row['price'], $row['currency']),
            'url' => cmfGetUrl('/shipyards/yachts/', array($shipyards['uri'], $row['uri']))
        );
        list(,,, $mYachts[$id]['width']) = getimagesize($mYachts[$id]['image']);
    }

    cmfCache::setParam('shipyards', array($infoId, $shipyardsId), array($info, $mMenu, $request, $headerId, $mPath, $shipyards, $mYachts), 'shipyards');
}

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
cmfMenu::setH1($shipyards['name']);
cmfGlobal::set('body', 'rent');
$this->setTeplates('main.yachts.php');

cmfSeo::set('title', $shipyards['title']);
cmfSeo::set('keywords', $shipyards['keywords']);
cmfSeo::set('description', $shipyards['description']);
$this->assing('shipyards', $shipyards);
$this->assing('mYachts', $mYachts);


list($phone1, $phone2) = explode(": ", cmfConfig::get('site', 'shipyardsPhone'));
$this->assing2('phone1', $phone1);
$this->assing2('phone2', $phone2);
?>
