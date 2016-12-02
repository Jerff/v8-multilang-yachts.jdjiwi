<?php

//pre(cmfDebug::isError());
if (cmfDebug::isError()) {
}
cmfHeader::setVersionJs('/sourseCompile/jsCompile.js');
cmfHeader::setVersionCss('/sourseCompile/cssCompile.css');

//pre(cMobileDetect::isMobile());
//exit;

cmfHeader::setVersion(18);
cmfHeader::setJsCompile('/sourseJs/jquery-1.8.2.min.js');
cmfHeader::setJsCompile('/sourseJs/jquery.easing.1.3.min.js');
cmfHeader::setJsCompile('/sourseJs/JsHttpRequest.min.js');
cmfHeader::setJsCompile('/sourseJs/json.min.js');

cmfHeader::setJsCompile('/sourseJs/cmf.function.js');
cmfHeader::setJsCompile('/sourseJs/cmf-0.1.js');
cmfHeader::setJsCompile('/sourseJs/cmf.ajax.js');
cmfHeader::setJsCompile('/sourseJs/cmf.ajax.driver.js');
cmfHeader::setJsCompile('/sourseJs/cmf.style.js');
cmfHeader::setJsCompile('/sourseJs/cmf.form.js');
cmfHeader::setJsCompile('/sourseJs/cmf.main.js');
cmfHeader::setJsCompile('/sourseJs/loadDocument.js');



cmfHeader::setJsCompile('/js/plagin.first/jquery-ui.min.js');

cmfHeader::setJsCompile('/js/plagin/jquery.maskedinput-1.3.min.js');
cmfHeader::setJsCompile('/js/plagin/jquery.ajaxscroll.min.js');
cmfHeader::setJsCompile('/js/plagin/highslide-with-gallery.packed.js');

cmfHeader::setJsCompile('/js/plagin/jquery.fancybox.pack.js');

cmfHeader::setJsCompile('/js/plagin/jquery.jscrollpane.min.js');
cmfHeader::setJsCompile('/js/plagin/jquery.cookie.js');

cmfHeader::setJsCompile('/js/plagin/jquery.dropdownPlain.js');
cmfHeader::setJsCompile('/js/plagin/cusel-min-2.5.js');
cmfHeader::setJsCompile('/js/plagin/jquery.checkbox.js');
cmfHeader::setJsCompile('/js/plagin/jquery.radio.js');

cmfHeader::setJsCompile('/js/plagin/jquery.nivo.slider.pack.js');

cmfHeader::setJsCompile('/js/plagin/jquery.touchwipe.min.js');

cmfHeader::setJs('/js/tabs.js');
cmfHeader::setJs('/js/function.js');
cmfHeader::setJs('/js/init_slidebar.js');





cmfHeader::setCssCompile('/sourseCss/cmfStyle.css');
cmfHeader::setCssCompile('/css/_style.css');
cmfHeader::setCssCompile('/css/reset.css');
cmfHeader::setCssCompile('/css/cusel.css');
cmfHeader::setCssCompile('/css/blog.css');
cmfHeader::setCssCompile('/css/font-awesome.min.css');
cmfHeader::setCss('/css/mobile/mobile.css');
cmfHeader::setCssCompile('/css/nivo-slider.css');
cmfHeader::setCssCompile('/css/jquery.fancybox.css');
cmfHeader::setCssCompile('/css/highslide.css');
cmfHeader::setCssCompile('/css/jquery-ui.min.css');



if (cmfPages::isMain('/index/') or cmfPages::isMain('/test/')) {
    cmfHeader::setJs('/js/prettyPhoto/js/jquery.prettyPhoto.js');
    cmfHeader::setCss('/js/prettyPhoto/css/prettyPhoto.css');


    cmfHeader::setCss('/js/videoslider/css/base/advanced-slider-base.css');
    cmfHeader::setCss('/js/videoslider/css/minimal-small/minimal-small.css');
    cmfHeader::setCss('/js/videoslider/css/video-js/video-js.min.css');
    cmfHeader::setCss('/js/videoslider/css/video-slider.css');

    cmfHeader::setJs('/js/videoslider/js/froogaloop.min.js');
    cmfHeader::setJs('/js/videoslider/js/video.min.js');
    cmfHeader::setJs('/js/videoslider/js/jquery.videoController.min.js');
    cmfHeader::setJs('/js/videoslider/js/jquery.advancedSlider.min.js');
}


$isMain = cmfPages::isMain('/index/');
if ($phone = cmfCache::get('header' . $isMain)) {
    list($phone, $mMenu, $head, $sliderTime, $mCounters, $mLang, $networkL, $networkR) = $phone;
} else {

    $sql = cmfRegister::getSql();
    $head = cmfConfig::get('seo', 'head');
    $sliderTime = cmfConfig::get('site', 'sliderTime');
    $networkL = cmfConfig::get('site', 'blogFallover');
    $networkR = cmfConfig::get('site', 'blogNetwork');
    $res = $sql->placeholder("SELECT lang, phone FROM ?t WHERE id='main'", db_main_lang)
            ->fetchRowAll(0, 1);
//    , cmfLang::getId()
    $phone = array();
    foreach ($res as $lang => $value) {
        foreach (explode("\n", $value) as $id => $line) {
            $line = array_map('trim', explode(":", $line));
            if (cmfLang::getId() === $lang) {
                $phone[$id]['name'] = $line[0];
                $phone[$id]['phone'] = $line[1];
            } else {
                $phone[$id]['name2'] = $line[0];
            }
        }
    }

    $mLang = cmfLang::getMainList();
    $mMenu = cmfMenu::getHeader();

//    $res = $sql->placeholder("SELECT logo, image_title, uri FROM ?t WHERE logo IS NOT NULL AND logo!='' AND isHeader='yes' AND visible='yes' ORDER BY main, pos", db_shipyards)
//            ->fetchAssocAll();
//    $_shipyards = array();
//    foreach ($res as $row) {
//        $_shipyards[] = array('title' => htmlspecialchars($row['image_title']),
//            'logo' => cmfBaseImg . cmfPathShipyardsLogo . $row['logo'], //str_replace('.png', '.gif', $row['logo']),
//            'url' => cmfGetUrl('/shipyards/one/', array($row['uri'])));
//    }

    if ($isMain) {
        $mCounters = $sql->placeholder("SELECT `type`, id, counters FROM ?t WHERE visible='yes' AND `type` IN('head', 'header') ORDER BY pos ", db_seo_counters)
                ->fetchRowAll(0, 1, 2);
    } else {
        $mCounters = $sql->placeholder("SELECT `type`, id, counters FROM ?t WHERE main='no' AND visible='yes' AND `type` IN('head', 'header') ORDER BY pos ", db_seo_counters)
                ->fetchRowAll(0, 1, 2);
    }

    cmfCache::set('header' . $isMain, array($phone, $mMenu, $head, $sliderTime, $mCounters, $mLang, $networkL, $networkR), 'menu,shipyards,contact');
}
cmfMenu::select('$headerId', $mMenu);
//if (isset($mMenu[cmfGlobal::get('$headerId')])) {
//    $mMenu[cmfGlobal::get('$headerId')]['sel'] = 1;
//    if (isset($mMenu[cmfGlobal::get('$headerId')]['childs'][cmfGlobal::get('$headerSubId')])) {
//        $mMenu[cmfGlobal::get('$headerId')]['childs'][cmfGlobal::get('$headerSubId')]['sel'] = 1;
//    }
//}
$arendaPhone = cmfGlobal::get('arendaPhone');
$phoneKey = cmfGlobal::get('$phoneId');
if (empty($arendaPhone)) {
    $arendaPhone = 'в Одессе';
}
if (!empty($arendaPhone) and empty($phoneKey)) {
    foreach ($phone as $key => $value) {
        if ($arendaPhone == $value['name'] or $arendaPhone == $value['name2']) {
            $phoneKey = $key;
        }
        $phone[$key]['tel'] = cmfContent::pareseTel($value['phone']);
    }
}
if (isset($phone[$phoneKey])) {
    $phone[$phoneKey]['sel'] = 1;
} else {
    $phone[$phoneKey = key($phone)]['sel'] = 1;
}
//pre($arendaPhone, $phoneKey, $phone);

foreach ($mLang as $k => $v) {
    $mLang[$k]['url'] = cmfChangeLang($k, cmfPages::url());
}

$arendaPhone = cmfGlobal::get('arendaPhone');



list($networkL, $networkR) = str_replace(
        array('%url%', '%header%'), array(cmfPages::getUrl(), cmfMenu::h1()), array($networkL, $networkR));
$this->assing2('networkL', '', $networkL);
//$this->assing('networkR', '', $networkR);


$this->assing2('menuId', cmfLang::getLang());
$this->assing('isMain', $isMain);

$this->assing('head', $head);
$this->assing('sliderTime', $sliderTime);
$this->assing('phone', $phone);
$this->assing('phoneNumber', $phone[$phoneKey]);

$this->assing('mMenu', $mMenu);
$this->assing('mLang', $mLang);
$this->assing2('headCounters', implode('', get($mCounters, 'head', array())));
$this->assing2('headerCounters', implode('', get($mCounters, 'header', array())));
//$this->assing('_shipyards', $_shipyards);

$this->assing2('index', cmfGetUrl('/index/'));
$this->assing2('rssUrl', cmfGetUrl('/news/rss/'));

$this->assing2('searchUrl', cmfGetUrl('/search/', array('new')));
$this->assing2('searchName', cmfString::specialchars(cmfGlobal::is('$searchName') ? cmfGlobal::get('$searchName') : word('Поиск по сайту')));
$this->assing2('searchDefault', word('Поиск по сайту'));

$this->assing2('body', cmfGlobal::get('body', 'index'));
?>
