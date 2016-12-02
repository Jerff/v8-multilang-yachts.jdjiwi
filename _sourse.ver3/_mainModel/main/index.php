<?php
cmfGlobal::set('body', 'index');
$search = $this->run('/menu/search/');

$sql = cmfRegister::getSql();
$main = $sql->placeholder("SELECT id, image FROM ?t WHERE id='main' LIMIT 0,1", db_main)
        ->fetchAssoc();
$list = $sql->placeholder("SELECT lang, header, mainMenu, content, title, keywords, description FROM ?t WHERE id='main'", db_main_lang)
        ->fetchAssocAll('lang');
cmfLang::data($main, $list);
cmfContent::replace($this, $main['content']);
$this->assing('header', $main['header']);
$this->assing('content', $main['content']);
$this->assing('mainMenu', $main['mainMenu']);

cmfSeo::set('title', $main['title']);
cmfSeo::set('keywords', $main['keywords']);
cmfSeo::set('description', $main['description']);

$res = $sql->placeholder("SELECT id, image, video, video_style, video_image, link FROM ?t WHERE page='main' AND parent='main' AND visible='yes' ORDER BY main, pos DESC", db_main_slider)
        ->fetchAssocAll('id');
$list = $sql->placeholder("SELECT id, lang, name, notice FROM ?t WHERE id ?@ AND page='main' AND parent='main'", db_main_slider_lang, array_keys($res))
        ->fetchAssocAll('id', 'lang');
$mSlider = array();
foreach ($res as $id => $row) {
    cmfLang::data($row, get($list, $id));
    $mSlider[$row['id']] = array(
        'title' => empty($row['notice']) ? htmlspecialchars($row['name']) : '#notice' . $row['id'],
        'alt' => htmlspecialchars($row['name']),
        'link' => $row['link'],
        'video' => $row['video'],
        'video_style' => $row['video_style'],
        'video_image' => cmfPathMain . $row['video_image'],
        'notice' => $row['notice'],
        'image' => cmfPathMain . $row['image']
    );
}
$this->assing2('slider', cmfContent::indexSliderVideo($mSlider));


//$res = $sql->placeholder("SELECT id, date, uri FROM ?t WHERE visible='yes' ORDER BY `main`, date DESC LIMIT 0, ?i", db_news, cmfConfig::get('site', 'mainNewsView'))
//        ->fetchAssocAll('id');
//$list = $sql->placeholder("SELECT id, lang, header, notice FROM ?t WHERE id ?@", db_news_lang, array_keys($res))
//        ->fetchAssocAll('id', 'lang');
//$_news = array();
//foreach ($res as $id => $row) {
//    cmfLang::data($row, get($list, $id));
//    $_news[] = array('date' => date('d.m.Y', strtotime($row['date'])),
//        'name' => $row['header'],
//        'notice' => nl2br($row['notice']),
//        'url' => cmfGetUrl('/news/item/', array($row['uri'])));
//}
//$this->assing('_news', $_news);
//$this->assing2('newsPath', cmfGetUrl('/news/'));

$res = $sql->placeholder("SELECT id, uri, view, autor, date FROM ?t WHERE visible='yes' ORDER BY `main`, date DESC LIMIT 0, ?i", db_blog, cmfConfig::get('site', 'mainNewsView'))
        ->fetchAssocAll('id');
$list = $sql->placeholder("SELECT id, lang, name, notice FROM ?t WHERE id ?@", db_blog_lang, array_keys($res))
        ->fetchAssocAll('id', 'lang');
$_news = array();
foreach ($res as $id => $row) {
    cmfLang::data($row, get($list, $id));
    $_news[] = array('date' => date('d.m.Y', strtotime($row['date'])),
        'name' => $row['name'],
        'notice' => cmfSubContent(strip_tags($row['notice']), 0, 200),
        'url' => cmfGetUrl('/blog/item/', array($row['uri'])));
}
$this->assing('_news', $_news);
$this->assing2('newsPath', cmfGetUrl('/blog/'));


//$res = $sql->placeholder("SELECT id, date, uri FROM ?t WHERE visible='yes' ORDER BY `main`, date DESC LIMIT 0, ?i", db_article, cmfConfig::get('site', 'mainArticleView'))
//        ->fetchAssocAll('id');
//$list = $sql->placeholder("SELECT id, lang, header, notice FROM ?t WHERE id ?@", db_article_lang, array_keys($res))
//        ->fetchAssocAll('id', 'lang');
//$_article = array();
//foreach ($res as $id => $row) {
//    cmfLang::result($row, get($list, $id));
//    $_article[$id] = array('date' => date('d/m/Y', strtotime($row['date'])),
//        'header' => $row['header'],
//        'notice' => $row['notice'],
//        'url' => cmfGetUrl('/article/one/', array($row['uri'])));
//}
//$_article = array_chunk($_article, 3);
//$this->assing('_article', $_article);


cmfLoad('catalog/function');
;
$paramValue = cmfParam::getParamId($paramId = cmfConfig::get('site', 'brokerageParam'));
$lengtValue = cmfParam::getParamId($lengthId = cmfConfig::get('site', 'brokerageShowcaseParam'));
$res = $sql->placeholder("SELECT y.`type`, y.id, y.uri, y.name, y.image_best, y.image_title, y.param, y.price, y.currency, y.shipyardsName AS sName, t.uri AS tUri FROM ?t y LEFT JOIN ?t t ON(y.type=t.id) WHERE t.visible='yes' AND y.menu='yes' AND y.visible='yes' ORDER BY y.pos, y.name LIMIT 0, 4", db_brokerage_yachts, db_brokerage_type)
        ->fetchAssocAll();
$_yachts = array();
foreach ($res as $row) {
    $_yachts[$row['id']] = array('shipyards' => $row['sName'],
        'name' => $row['name'],
        'param' => cmfParam::selectParam($row['param'], $paramId, $paramValue, false),
        'lengt' => cmfParam::selectParam($row['param'], $lengthId, $lengtValue),
        'image' => cmfBaseImg . cmfPathBrokerageYachts . $row['image_best'],
        'title' => htmlspecialchars($row['image_title']),
        'price' => cmfPrice::view($row['price'], $row['currency']),
        'url' => cmfGetUrl('/brokerage/yachts/', array($row['tUri'], $row['uri'])));
}
$this->assing2('_yachts', $_yachts);
$this->assing2('brokerageUrl', cmfGetUrl('/brokerage/'));
$this->assing('search', $search);

$this->assing2('networkR', cmfConfig::get('site', 'blogNetwork'));
?>