<?php

//cl("sd",1);

cmfLoad('catalog/function');
$infoId = cmfGlobal::get('$infoId');
$typeId = cmfGlobal::get('$param1');
$yachtsId = cmfGlobal::get('$param2');
if (!$infoId or !$typeId or !$yachtsId)
    return 404;

if ($info = cmfCache::getParam('brokerage/yacht', array($infoId, $typeId, $yachtsId))) {
    list($info, $mMenu, $request, $headerId, $mPath, $type, $yachts, $notice, $mFoto, $mPlan, $startUrl, $endUrl) = $info;
} else {

    if (!$info = cmfContent::info($infoId))
        return 404;
    list($info, $request, $headerId, $mPath) = $info;
    $mMenu = cmfContent::initSubMenu($info, $infoId);
//    pre($info);
//    exit;
//    $info = $sql->placeholder("SELECT id, path, parent, pages, request, isUri FROM ?t WHERE id=? AND isVisible='yes'", db_menu_info, $infoId)
//            ->fetchAssoc();
//    if (!$info)
//        return 404;
//    $list = $sql->placeholder("SELECT lang, name, menu, content, title, keywords, description FROM ?t WHERE id=?", db_menu_info_lang, $infoId)
//            ->fetchAssocAll('lang');
//    cmfLang::data($info, $list);
//
//    $mPath = array();
//    if (!empty($info['path'])) {
//        $res = $sql->placeholder("SELECT id, parent, isUri FROM ?t WHERE id ?@ AND visible='yes' ORDER BY level", db_menu_info, cmfString::pathToArray($info['path']))
//                ->fetchAssocAll('id');
//        $list = $sql->placeholder("SELECT id, lang, name, menu FROM ?t WHERE id ?@", db_menu_info_lang, array_keys($res))
//                ->fetchAssocAll('id', 'lang');
//        foreach ($res as $id => $row) {
//            cmfLang::data($row, get($list, $id));
//            if (empty($row['parent'])) {
//                cmfMenu::setSelect('$headerId', $id . 'menu');
//            }
//            $mPath[cmfGetUrl('/info/', array($row['isUri']))] = empty($row['menu']) ? $row['name'] : $row['menu'];
////            cmfMenu::add(empty($row['menu']) ? $row['name'] : $row['menu'], cmfGetUrl('/info/', array($row['isUri'])));
//        }
//    }
//    $mPath[cmfGetUri('/info/', array($info['isUri']))] = empty($info['menu']) ? $info['name'] : $info['menu'];
//    cmfMenu::add(empty($info['menu']) ? $info['name'] : $info['menu'], cmfGetUri('/info/', array($info['isUri'])))


    $sql = cmfRegister::getSql();
    $type = $sql->placeholder("SELECT id, uri FROM ?t WHERE id=? AND visible='yes'", db_brokerage_type, $typeId)
            ->fetchAssoc();
    if (!$type)
        return 404;
    $list = $sql->placeholder("SELECT lang, name, menu, content, title, keywords, description FROM ?t WHERE id=?", db_brokerage_type_lang, $typeId)
            ->fetchAssocAll('lang');
    cmfLang::data($type, $list);
    $mPath[cmfGetUri('/brokerage/type/', array($type['uri']))] = $type['name'];
//    cmfMenu::add($type['name'], cmfGetUrl('/brokerage/type/', array($type['uri'])));


    $yachts = $sql->placeholder("SELECT * FROM ?t WHERE id=? AND visible='yes'", db_brokerage_yachts, $yachtsId)
            ->fetchAssoc();
    if (!$yachts)
        return 404;
    $list = $sql->placeholder("SELECT * FROM ?t WHERE id=?", db_brokerage_yachts_lang, $yachtsId)
            ->fetchAssocAll('lang');
    cmfLang::data($yachts, $list);

    $title = $yachts['name'];


    $yachts['price'] = cmfPrice::view($yachts['price'], $yachts['currency']);
    $mFoto = array();
    if ($yachts['image_main']) {
        $mFoto[$id = -1] = array(
            'title' => htmlspecialchars($yachts['image_title']),
            'main' => cmfBaseOld . cmfPathBrokerageYachts . $yachts['image_main'],
            'small' => cmfImage::preview(cmfBaseOld . cmfPathBrokerageYachts . $yachts['image_main'], 500, 335, $yachts['uri'], 'b-main', $yachts['id'],  $yachts['update']),
            'preview' => cmfImage::preview(cmfBaseOld . cmfPathBrokerageYachts . $yachts['image_main'], 75, 50, $yachts['uri'], 'b-main', $yachts['id'],  $yachts['update'])
        );
        list(,,, $mFoto[$id]['small-width']) = getimagesize($mFoto[$id]['small']);
        list(,,, $mFoto[$id]['preview-width']) = getimagesize($mFoto[$id]['preview']);

        $yachts['image_main'] = cmfImage::preview(cmfBaseOld . cmfPathBrokerageYachts . $yachts['image_main'], 944, 500,$yachts['uri'], 'b-m',$yachts['id'],$yachts['update']);
        list(,,, $yachts['width']) = getimagesize($yachts['image_main']);
        $yachts['image_title'] = htmlspecialchars($yachts['image_title']);
    }
    $mPath[] = $yachts['name'];
//pre($yachts);

    $notice = cmfParam::generateNotice($yachts['param'], 'brokerage');
    $paramId = cmfConfig::get('site', 'brokerageParam');


    $res = $sql->placeholder("SELECT id, name, time, image_main FROM ?t WHERE yachts=? AND visible='yes' ORDER BY main, pos", db_brokerage_yachts_foto, $yachtsId)
            ->fetchAssocAll();
//    $mFoto = array();
    foreach ($res as $row) {
        $mFoto[$id = $row['id']] = array(
            'title' => htmlspecialchars(empty($row['name']) ? $title . ' ' . $row['time'] : $row['name']),
            'main' => cmfBaseOld . cmfPathBrokerageYachtsFoto . $row['image_main'],
            'small' => cmfImage::preview(cmfBaseOld . cmfPathBrokerageYachtsFoto . $row['image_main'], 500, 335, $yachts['uri'], 'b-foto', $row['id'],  $row['time']),
            'preview' => cmfImage::preview(cmfBaseOld . cmfPathBrokerageYachtsFoto . $row['image_main'], 75, 50, $yachts['uri'], 'b-foto', $row['id'],  $row['time'])
        );
        list(,,, $mFoto[$id]['small-width']) = getimagesize($mFoto[$id]['small']);
        list(,,, $mFoto[$id]['preview-width']) = getimagesize($mFoto[$id]['preview']);
    }


    $res = $sql->placeholder("SELECT id, name, time, image_main FROM ?t WHERE yachts=? AND visible='yes' ORDER BY main, pos", db_brokerage_yachts_plan, $yachtsId)
            ->fetchAssocAll();
    $mPlan = array();

    foreach ($res as $row) {
        $mFoto[$id = $row['id']] = array(
            'title' => htmlspecialchars(empty($row['name']) ? $title . ' ' . $row['time'] : $row['name']),
            'main' => cmfBaseOld . cmfPathBrokerageYachtsPlan . $row['image_main'],
            'small' => cmfImage::preview(cmfBaseOld . cmfPathBrokerageYachtsPlan . $row['image_main'], 500, 335, $yachts['uri'], 'b-plan', $row['id'],  $row['time']),
            'preview' => cmfImage::preview(cmfBaseOld . cmfPathBrokerageYachtsPlan . $row['image_main'], 75, 50, $yachts['uri'], 'b-plan', $row['id'],  $row['time'])
        );
        list(,,, $mFoto[$id]['small-width']) = getimagesize($mFoto[$id]['small']);
        list(,,, $mFoto[$id]['preview-width']) = getimagesize($mFoto[$id]['preview']);
    }


    $startUrl = $endUrl = false;
    $res = $sql->placeholder("SELECT y.id, t.uri, y.uri FROM ?t y LEFT JOIN ?t t ON(t.id=y.type) WHERE y.type=? AND t.visible='yes' AND y.visible='yes' ORDER BY y.name", db_brokerage_yachts, db_brokerage_type, $yachts['type'])
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
            $startUrl = cmfGetUrl('/brokerage/yachts/', array($sUri, $uri));
            if (!$item) {
                list($id, $sUri, $uri) = end($res);
            } else {
                list($sUri, $uri) = $item;
            }
            $endUrl = cmfGetUrl('/brokerage/yachts/', array($sUri, $uri));
            break;
        }
        $item = array($sUri, $uri);
    }

    cmfCache::setParam('brokerage/yacht', array($infoId, $typeId, $yachtsId), array($info, $mMenu, $request, $headerId, $mPath, $type, $yachts, $notice, $mFoto, $mPlan, $startUrl, $endUrl), 'photo');
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


$this->assing('type', $type);
$this->assing('yachts', $yachts);
if ($notice) {
    $this->assing('notice', $notice);
    list($phone1, $phone2) = explode(": ", cmfConfig::get('site', 'brokeragePhone'));
    $this->assing2('phone1', $phone1);
    $this->assing2('phone2', $phone2);
}
$this->assing2('mFoto', $mFoto);
$this->assing2('mPlan', $mPlan);

$this->assing2('startUrl', $startUrl);
$this->assing2('endUrl', $endUrl);


$this->assing2('saleUrl', cmfGetUrl('/sale/request/') . '?type=brokerage&id=' . $yachtsId);

cmfSeo::set('title', $yachts['title']);
cmfSeo::set('keywords', $yachts['keywords']);
cmfSeo::set('description', $yachts['description']);
cmfGlobal::set('body', 'yacht');

cmfHeader::setJs('/js/external/jquery.mousewheel.min.js');
cmfHeader::setJs('/js/sliderkit/js/jquery.sliderkit.1.9.2.pack.js');
cmfHeader::setCss('/js/sliderkit/css/sliderkit-core.css');
cmfHeader::setCss('/js/sliderkit/css/sliderkit-demos.css');
?>
