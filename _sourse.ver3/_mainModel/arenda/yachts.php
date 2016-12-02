<?php


cmfLoad('catalog/function');
$infoId = cmfGlobal::get('$infoId');
$arendaId = cmfGlobal::get('$param1');
$yachtsId = cmfGlobal::get('$param2');
if (!$infoId or !$arendaId or !$yachtsId)
    return 404;


//pre($infoId, $arendaId, $yachtsId);
if ($info = cmfCache::getParam('arenda/yacht', array($infoId, $arendaId, $yachtsId))) {
    list($info, $mMenu, $request, $headerId, $mPath, $type, $arendaPhone, $yachts, $notice, $mFoto, $mPlan, $startUrl, $endUrl) = $info;

} else {


    if (!$info = cmfContent::info($infoId))
        return 404;
    list($info, $request, $headerId, $mPath) = $info;
    $mMenu = cmfContent::initSubMenu($info, $infoId);


    $sql = cmfRegister::getSql();
    $arenda = $sql->placeholder("SELECT id, path, phone, level, type, isUri FROM ?t WHERE id=? AND visible='yes'", db_arenda, $arendaId)
            ->fetchAssoc();
    if (!$arenda)
        return 404;
    $list = $sql->placeholder("SELECT lang, name, content, title, keywords, description FROM ?t WHERE id=?", db_arenda_lang, $arendaId)
            ->fetchAssocAll('lang');
    cmfLang::data($arenda, $list);
    $arendaPhone = $arenda['phone'];


    $mList = array($arendaId);
    $isUkraine = $arenda['id'] == arendaMenu;
    if (strpos($arenda['path'], "[3]") !== false) {
        $arenda['path'] = substr($arenda['path'], strpos($arenda['path'], "[3]") + 3);
    }
    if (!empty($arenda['path']) and !$isUkraine) {
        $res = $sql->placeholder("SELECT id, name, phone, isUri FROM ?t WHERE id ?@ AND isVisible='yes' ORDER BY level", db_arenda, cmfString::pathToArray($arenda['path']))
                ->fetchAssocAll();
        $menu = array();
        foreach ($res as $row) {
            $mList[] = $row['id'];
            $mPath[cmfGetUrl('/arenda/', array($row['isUri']))] = $row['name'];
        }
        if(!empty($arenda['phone'])) {
            $arendaPhone = $row['phone'];
        }
    }
    $mPath[cmfGetUrl('/arenda/', array($arenda['isUri']))] = $arenda['name'];


    $yachts = $sql->placeholder("SELECT * FROM ?t WHERE id=? AND visible='yes'", db_arenda_yachts, $yachtsId)
            ->fetchAssoc();
    if (!$yachts)
        return 404;
    $list = $sql->placeholder("SELECT * FROM ?t WHERE id=?", db_arenda_yachts_lang, $yachtsId)
            ->fetchAssocAll('lang');
    cmfLang::data($yachts, $list);
    $title = $yachts['name'];
    $mPath[] = $yachts['name'];

    $yachts['priceHour'] = cmfPrice::view2($yachts['priceHour'], $yachts['currency']);
    $yachts['priceLightDay'] = cmfPrice::view2($yachts['priceLightDay'], $yachts['currency']);
    $yachts['priceDay'] = cmfPrice::view2($yachts['priceDay'], $yachts['currency']);
    $yachts['priceWeek'] = cmfPrice::view2($yachts['priceWeek'], $yachts['currency']);


    $mFoto = array();
    if ($yachts['image_main']) {
//        if(cmfDebug::isError()) {
//            $img = cmfImage::preview(cmfBaseOld . cmfPathArendaYachts . $yachts['image_main'], 75, 50, $yachts['uri'], 'a-main', $yachts['id'],  $yachts['update']);
//            pre($img);
//            exit;
//        }
        $mFoto[$id = -1] = array(
            'title' => htmlspecialchars($yachts['image_title']),
            'main' => cmfBaseOld . cmfPathArendaYachts . $yachts['image_main'],
            'small' => cmfImage::preview(cmfBaseOld . cmfPathArendaYachts . $yachts['image_main'], 500, 335, $yachts['uri'], 'a-main', $yachts['id'],  $yachts['update']),
            'preview' => cmfImage::preview(cmfBaseOld . cmfPathArendaYachts . $yachts['image_main'], 75, 50, $yachts['uri'], 'a-main', $yachts['id'],  $yachts['update'])
        );
        list(,,, $mFoto[$id]['small-width']) = getimagesize($mFoto[$id]['small']);
        list(,,, $mFoto[$id]['preview-width']) = getimagesize($mFoto[$id]['preview']);

        $yachts['image_main'] = cmfImage::preview(cmfBaseOld . cmfPathArendaYachts . $yachts['image_main'], 944, 500,$yachts['uri'], 'a-m',$yachts['id'],$yachts['update']);
        list(,,, $yachts['width']) = getimagesize($yachts['image_main']);
        $yachts['image_title'] = htmlspecialchars($yachts['image_title']);

    }


    $notice = cmfParam::generateNotice($yachts['param'], 'arenda');


    $type = $sql->placeholder("SELECT * FROM ?t WHERE id=? AND visible='yes'", db_arenda_type, $yachts['type'])
            ->fetchAssoc();
    if (!$type)
        return 404;
    $list = $sql->placeholder("SELECT * FROM ?t WHERE id=?", db_arenda_type_lang, $yachts['type'])
            ->fetchAssocAll('lang');
    cmfLang::data($type, $list);


    $res = $sql->placeholder("SELECT id, name, time, image_main FROM ?t WHERE yachts=? AND visible='yes' ORDER BY main, pos", db_arenda_yachts_foto, $yachtsId)
            ->fetchAssocAll();

    foreach ($res as $row) {
        $mFoto[$id = $row['id']] = array(
            'title' => htmlspecialchars(empty($row['name']) ? $title . ' ' . $row['time'] : $row['name']),
            'main' => cmfBaseOld . cmfPathArendaYachtsFoto . $row['image_main'],
            'small' => cmfImage::preview(cmfBaseOld . cmfPathArendaYachtsFoto . $row['image_main'], 500, 335, $yachts['uri'], 'a-foto', $row['id'],  $row['time']),
            'preview' => cmfImage::preview(cmfBaseOld . cmfPathArendaYachtsFoto . $row['image_main'], 75, 50, $yachts['uri'], 'a-foto', $row['id'],  $row['time'])
        );
        list(,,, $mFoto[$id]['small-width']) = getimagesize($mFoto[$id]['small']);
        list(,,, $mFoto[$id]['preview-width']) = getimagesize($mFoto[$id]['preview']);
    }

    $res = $sql->placeholder("SELECT id, name, time, image_main FROM ?t WHERE yachts=? AND visible='yes' ORDER BY main, pos", db_arenda_yachts_plan, $yachtsId)
            ->fetchAssocAll();
    $mPlan = array();
    foreach ($res as $row) {
        $mFoto[$id = $row['id']] = array(
            'title' => htmlspecialchars(empty($row['name']) ? $title . ' ' . $row['time'] : $row['name']),
            'main' => cmfBaseOld . cmfPathArendaYachtsPlan . $row['image_main'],
            'small' => cmfImage::preview(cmfBaseOld . cmfPathArendaYachtsPlan . $row['image_main'], 500, 335,$yachts['uri'], 'a-plan', $row['id'],  $row['time']),
            'preview' => cmfImage::preview(cmfBaseOld . cmfPathArendaYachtsPlan . $row['image_main'], 75, 50,$yachts['uri'], 'a-plan', $row['id'],  $row['time'])
        );
        list(,,, $mFoto[$id]['small-width']) = getimagesize($mFoto[$id]['small']);
        list(,,, $mFoto[$id]['preview-width']) = getimagesize($mFoto[$id]['preview']);
    }


    $arendaAll[$arenda['id']] = $arenda['level'];
    $isLevel2 = array_search(2, $arendaAll);
    $isLevel1 = array_search(1, $arendaAll);
    foreach ($arendaAll as $id => $level) {
        if ($isLevel2) {
            if ($level < 2)
                unset($arendaAll[$id]);
        } elseif ($isLevel1) {
            if ($level < 1)
                unset($arendaAll[$id]);
        }
    }
    $startUrl = $endUrl = false;
    if ($arendaAll) {
        foreach ($arendaAll as $k => $v) {
            $where['id'][$k] = $k;
            $where[] = $sql->getQuery(" OR path LIKE '%[?i]%'", $k);
        }
        $res = $sql->placeholder("SELECT y.id, t.uri, y.uri FROM ?t y LEFT JOIN ?t t ON(t.id=y.type) WHERE y.id IN(SELECT yachts FROM ?t WHERE arenda IN(SELECT id FROM ?t WHERE (?w) AND visible='yes')) AND y.type=? AND t.visible='yes' AND y.visible='yes' ORDER BY y.name", db_arenda_yachts, db_arenda_type, db_arenda_list, db_arenda, $where, $yachts['type'])
                ->fetchRowAll();
        $start = $item = false;
        ///pre($yachtsId);
        while (list(, list($id, $sUri, $uri)) = each($res)) {
            if (!$start)
                $start = array($sUri, $uri);
            if ($id == $yachtsId) {
                list(, list($id, $sUri, $uri)) = each($res);
                //pre($sUri, $uri);
                if (!$uri) {
                    list($sUri, $uri) = $start;
                }
                $startUrl = cmfGetUrl('/arenda/yachts/', array($sUri, $uri));
                if (!$item) {
                    list($id, $sUri, $uri) = end($res);
                } else {
                    list($sUri, $uri) = $item;
                }
                //pre($sUri, $uri);
                $endUrl = cmfGetUrl('/arenda/yachts/', array($sUri, $uri));
                break;
            }
            $item = array($sUri, $uri);
        }
    }

    cmfCache::setParam('arenda/yacht', array($infoId, $arendaId, $yachtsId), array($info, $mMenu, $request, $headerId, $mPath, $type, $arendaPhone, $yachts, $notice, $mFoto, $mPlan, $startUrl, $endUrl), 'photo');
}

cmfGlobal::set('arendaPhone', $arendaPhone);

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

if(isset($_COOKIE['netpeak'])){
	$uri = $yachts['uri'];
	//cl($yachts,1);
	//$yachts['image_main'] = "http://".cmfDomen."/images/yacht/arenda/".$uri."/main".".jpg";

	//cl($yachts['image_main'],1);
}

$this->assing('type', $type);
$this->assing('yachts', $yachts);
if ($notice) {
    $this->assing('notice', $notice);
    list($phone1, $phone2) = explode(": ", cmfConfig::get('site', 'arendaPhone'));
    $this->assing2('phone1', $phone1);
    $this->assing2('phone2', $phone2);
}
$this->assing2('mFoto', $mFoto);
$this->assing2('mPlan', $mPlan);

$this->assing2('arendaUrl', cmfGetUrl('/arenda/request/') . '?type=arenda&id=' . $yachtsId);

$this->assing2('startUrl', $startUrl);
$this->assing2('endUrl', $endUrl);



cmfSeo::set('title', $yachts['title']);
cmfSeo::set('keywords', $yachts['keywords']);
cmfSeo::set('description', $yachts['description']);
cmfGlobal::set('body', 'yacht');


cmfHeader::setJs('/js/external/jquery.mousewheel.min.js');
cmfHeader::setJs('/js/sliderkit/js/jquery.sliderkit.1.9.2.pack.js');
cmfHeader::setCss('/js/sliderkit/css/sliderkit-core.css');
cmfHeader::setCss('/js/sliderkit/css/sliderkit-demos.css');
return 1;



$arenda = $sql->placeholder("SELECT id, path, level, name, isUri FROM ?t WHERE id IN(SELECT arenda FROM ?t WHERE yachts=?) AND isVisible='yes' ORDER BY level, pos", db_arenda, db_arenda_list, $yachtsId)
        ->fetchAssoc();
$isArenda = (bool) $arenda;

$arendaAll = array();
if ($isArenda) {
    $isUkraine = $arenda['id'] == 3;
    if (!empty($arenda['path']) and !$isUkraine) {
        $res = $sql->placeholder("SELECT id, level, name, isUri FROM ?t WHERE id ?@ AND isVisible='yes' ORDER BY level", db_arenda, cmfString::pathToArray($arenda['path']))
                ->fetchAssocAll();
        $menu = array();
        foreach ($res as $row) {
            if ($row['id'] == 3) {
                $menu = array();
                $isUkraine = true;
            }
            $arendaAll[$row['id']] = $row['level'];
            $menu[$row['name']] = cmfGetUrl('/arenda/', array($row['isUri']));
        }
    }
}

$arendaAll[$arenda['id']] = $arenda['level'];
$isLevel2 = array_search(2, $arendaAll);
$isLevel1 = array_search(1, $arendaAll);
foreach ($arendaAll as $id => $level) {
    if ($isLevel2) {
        if ($level < 2)
            unset($arendaAll[$id]);
    } elseif ($isLevel1) {
        if ($level < 1)
            unset($arendaAll[$id]);
    }
}

if (!$isArenda or $isUkraine) {
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
if ($isArenda) {
    cmfGlobal::set('arendaId', $arenda['id']);
    if (isset($menu))
        foreach ($menu as $k => $v) {
            cmfMenu::add($k, $v);
        }

    cmfMenu::add($arenda['name'], cmfGetUrl('/arenda/', array($arenda['isUri'])));
}
cmfMenu::add($yachts['name'], cmfGetUrl('/arenda/yachts/', array($yachts['tUri'], $yachts['uri'])));



$title = $yachts['name'];
cmfMenu::setHeader($yachts['name']);
cmfMenu::setRequest('arenda');

cmfSeo::set('title', $yachts['title']);
cmfSeo::set('keywords', $yachts['keywords']);
cmfSeo::set('description', $yachts['description']);

cmfLoad('catalog/function');
$yachts['priceHour'] = cmfPrice::view2($yachts['priceHour'], $yachts['currency']);
$yachts['priceLightDay'] = cmfPrice::view2($yachts['priceLightDay'], $yachts['currency']);
$yachts['priceDay'] = cmfPrice::view2($yachts['priceDay'], $yachts['currency']);
$yachts['priceWeek'] = cmfPrice::view2($yachts['priceWeek'], $yachts['currency']);
if ($yachts['image_main']) {
    $yachts['image_main'] = cmfBaseOld . cmfPathArendaYachts . $yachts['image_main'];
    $yachts['image_title'] = htmlspecialchars($yachts['image_title']);

}
$this->assing('yachts', $yachts);

$notice = cmfParam::generateNotice($yachts['param'], 'arenda');
if ($notice) {
    $this->assing('notice', $notice);
    list($phone1, $phone2) = explode(": ", cmfConfig::get('site', 'arendaPhone'));
    $this->assing2('phone1', $phone1);
    $this->assing2('phone2', $phone2);
}



$this->assing2('saleUrl', cmfGetUrl('/request/arenda/') . '?type=arenda&id=' . $yachtsId);


if ($arendaAll) {
    foreach ($arendaAll as $k => $v) {
        $where['id'][$k] = $k;
        $where[] = $sql->getQuery(" OR path LIKE '%[?i]%'", $k);
    }
    $res = $sql->placeholder("SELECT y.id, t.uri, y.uri FROM ?t y LEFT JOIN ?t t ON(t.id=y.type) WHERE y.id IN(SELECT yachts FROM ?t WHERE arenda IN(SELECT id FROM ?t WHERE (?w) AND visible='yes')) AND y.type=? AND t.visible='yes' AND y.visible='yes' ORDER BY y.name", db_arenda_yachts, db_arenda_type, db_arenda_list, db_arenda, $where, $yachts['type'])
            ->fetchRowAll();
    $start = $item = false;
    ///pre($yachtsId);
    while (list(, list($id, $sUri, $uri)) = each($res)) {
        if (!$start)
            $start = array($sUri, $uri);
        if ($id == $yachtsId) {
            list(, list($id, $sUri, $uri)) = each($res);
            //pre($sUri, $uri);
            if (!$uri) {
                list($sUri, $uri) = $start;
            }
            $this->assing2('endUrl', cmfGetUrl('/arenda/yachts/', array($sUri, $uri)));
            if (!$item) {
                list($id, $sUri, $uri) = end($res);
            } else {
                list($sUri, $uri) = $item;
            }
            //pre($sUri, $uri);
            $this->assing2('startUrl', cmfGetUrl('/arenda/yachts/', array($sUri, $uri)));
            break;
        }
        $item = array($sUri, $uri);
    }
}
?>
