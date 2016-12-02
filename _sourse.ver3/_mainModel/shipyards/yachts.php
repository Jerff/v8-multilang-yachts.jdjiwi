<?php

cmfLoad('catalog/function');
$infoId = cmfGlobal::get('$infoId');
$shipyardsId = cmfGlobal::get('$param1');
$yachtsId = cmfGlobal::get('$param2');
if (!$infoId or !$shipyardsId or !$yachtsId)
    return 404;


if ($info = cmfCache::getParam('shipyards/yacht', array($infoId, $shipyardsId, $yachtsId))) {
    list($info, $mMenu, $request, $headerId, $mPath, $shipyards, $yachts, $notice, $mFoto, $mPlan, $startUrl, $endUrl) = $info;
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
    $mPath[cmfGetUrl('/shipyards/one/', array($shipyards['uri']))] = $shipyards['name'];


    $yachts = $sql->placeholder("SELECT * FROM ?t WHERE shipyards=? AND id=? AND visible='yes'", db_shipyards_yachts, $shipyardsId, $yachtsId)
            ->fetchAssoc();
    if (!$yachts)
        return 404;
    $list = $sql->placeholder("SELECT * FROM ?t WHERE id=?", db_shipyards_yachts_lang, $yachtsId)
            ->fetchAssocAll('lang');
    cmfLang::data($yachts, $list);

    $mPath[] = $yachts['name'];
    $title = $yachts['name'];
    $yachts['price'] = cmfPrice::view($yachts['price'], $yachts['currency']);
    $mFoto = array();
    if ($yachts['image_main']) {
        $mFoto[$id = -1] = array(
            'title' => htmlspecialchars($yachts['image_title']),
            'main' => cmfBaseOld . cmfPathShipyardsYachts . $yachts['image_main'],
            'small' => cmfImage::preview(cmfBaseOld . cmfPathShipyardsYachts . $yachts['image_main'], 500, 335, $yachts['uri'], 's-main', $yachts['id'],  $yachts['update']),
            'preview' => cmfImage::preview(cmfBaseOld . cmfPathShipyardsYachts . $yachts['image_main'], 75, 50, $yachts['uri'], 's-main', $yachts['id'],  $yachts['update'])
        );
        list(,,, $mFoto[$id]['small-width']) = getimagesize($mFoto[$id]['small']);
        list(,,, $mFoto[$id]['preview-width']) = getimagesize($mFoto[$id]['preview']);

        $yachts['image_main'] = cmfImage::preview(cmfBaseOld . cmfPathShipyardsYachts . $yachts['image_main'], 944, 500,$yachts['uri'], 's-m',$yachts['id'],$yachts['update']);
        list(,,, $yachts['width']) = getimagesize($yachts['image_main']);
        $yachts['image_title'] = htmlspecialchars($yachts['image_title']);
    }
    $notice = cmfParam::generateNotice($yachts['param'], 'shipyards');


    $res = $sql->placeholder("SELECT id, name, time, image_main FROM ?t WHERE yachts=? AND visible='yes' ORDER BY main, pos", db_shipyards_yachts_foto, $yachtsId)
            ->fetchAssocAll();
//    $mFoto = array();
    foreach ($res as $row) {
        $mFoto[$id = $row['id']] = array(
            'title' => htmlspecialchars(empty($row['name']) ? $title . ' ' . $row['time'] : $row['name']),
            'main' => cmfBaseOld . cmfPathShipyardsYachtsFoto . $row['image_main'],
            'small' => cmfImage::preview(cmfBaseOld . cmfPathShipyardsYachtsFoto . $row['image_main'], 500, 335, $yachts['uri'], 's-foto', $row['id'],  $row['time']),
            'preview' => cmfImage::preview(cmfBaseOld . cmfPathShipyardsYachtsFoto . $row['image_main'], 75, 50, $yachts['uri'], 's-foto', $row['id'],  $row['time'])
        );
        list(,,, $mFoto[$id]['small-width']) = getimagesize($mFoto[$id]['small']);
        list(,,, $mFoto[$id]['preview-width']) = getimagesize($mFoto[$id]['preview']);
    }


    $res = $sql->placeholder("SELECT id, name, time, image_main FROM ?t WHERE yachts=? AND visible='yes' ORDER BY main, pos", db_shipyards_yachts_plan, $yachtsId)
            ->fetchAssocAll();
    $mPlan = array();
    foreach ($res as $row) {
        $mFoto[$id = $row['id']] = array(
            'title' => htmlspecialchars(empty($row['name']) ? $title . ' ' . $row['time'] : $row['name']),
            'main' => cmfBaseOld . cmfPathShipyardsYachtsPlan . $row['image_main'],
            'small' => cmfImage::preview(cmfBaseOld . cmfPathShipyardsYachtsPlan . $row['image_main'], 500, 335, $yachts['uri'], 's-plan', $row['id'],  $row['time']),
            'preview' => cmfImage::preview(cmfBaseOld . cmfPathShipyardsYachtsPlan . $row['image_main'], 75, 50, $yachts['uri'], 's-plan', $row['id'],  $row['time'])
        );
        list(,,, $mFoto[$id]['small-width']) = getimagesize($mFoto[$id]['small']);
        list(,,, $mFoto[$id]['preview-width']) = getimagesize($mFoto[$id]['preview']);
    }


    $startUrl = $endUrl = false;
    $res = $sql->placeholder("SELECT y.id, t.uri, y.uri FROM ?t y LEFT JOIN ?t t ON(t.id=y.shipyards) WHERE y.shipyards=? AND t.visible='yes' AND y.visible='yes' ORDER BY y.name", db_shipyards_yachts, db_shipyards, $yachts['shipyards'])
            ->fetchRowAll();
    $start = $item = false;
    while (list(, list($id, $sUri, $uri)) = each($res)) {
        if (!$start)
            $start = array($sUri, $uri);
        if ($id == $yachtsId) {

            list(, list($id, $sUri, $uri)) = each($res);
            if (!$uri) {
                list($sUri, $uri) = $start;
            }
            $startUrl = cmfGetUrl('/shipyards/yachts/', array($sUri, $uri));
            if (!$item) {
                list($id, $sUri, $uri) = end($res);
            } else {
                list($sUri, $uri) = $item;
            }
            $endUrl = cmfGetUrl('/shipyards/yachts/', array($sUri, $uri));
            break;
        }
        $item = array($sUri, $uri);
    }

    cmfCache::setParam('shipyards/yacht', array($infoId, $shipyardsId, $yachtsId), array($info, $mMenu, $request, $headerId, $mPath, $shipyards, $yachts, $notice, $mFoto, $mPlan, $startUrl, $endUrl), 'photo');
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

//cmfMenu::setH1($yachts['name']);
cmfGlobal::set('body', 'yacht');
//$this->setTeplates('main.yachts.php');

cmfSeo::set('title', $yachts['title']);
cmfSeo::set('keywords', $yachts['keywords']);
cmfSeo::set('description', $yachts['description']);

$this->assing('shipyards', $shipyards);
$this->assing('yachts', $yachts);
if ($notice) {
    $this->assing('notice', $notice);
}
list($phone1, $phone2) = explode(": ", cmfConfig::get('site', 'shipyardsPhone'));
$this->assing2('phone1', $phone1);
$this->assing2('phone2', $phone2);

$this->assing2('saleUrl', cmfGetUrl('/sale/request/') . '?type=shipyards&id=' . $yachtsId);

$this->assing2('mFoto', $mFoto);
$this->assing2('mPlan', $mPlan);

$this->assing2('startUrl', $startUrl);
$this->assing2('endUrl', $endUrl);



cmfHeader::setJs('/js/external/jquery.mousewheel.min.js');
cmfHeader::setJs('/js/sliderkit/js/jquery.sliderkit.1.9.2.pack.js');
cmfHeader::setCss('/js/sliderkit/css/sliderkit-core.css');
cmfHeader::setCss('/js/sliderkit/css/sliderkit-demos.css');
?>