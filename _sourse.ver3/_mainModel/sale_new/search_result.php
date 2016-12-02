<?php

if (isset($_GET['form'])) {
    $post = array(
        get($_POST, 'type'),
        implode('=', get($_POST, 'aType', array())),
        implode('=', array_map('urlencode', get($_POST, 'sType', array()))),
        get($_POST, 'minAPrice'),
        get($_POST, 'maxAPrice'),
        get($_POST, 'minSPrice'),
        get($_POST, 'maxSPrice'),
        get($_POST, 'minASize'),
        get($_POST, 'maxASize'),
        get($_POST, 'minSSize'),
        get($_POST, 'maxSSize'),
        get($_POST, 'charter'),
        urlencode(get($_POST, 'shipyard'))
    );
    $post = implode('-', $post);
    cmfRedirect(cmfGetUrl('/sale/new/search/result/', array($post)));
//    pre($_POST, $post);
//    exit;
}


cmfLoad('catalog/function');
$searchUri = cmfPages::getParam(1);
$post = explode('-', $searchUri);
$i = 0;
$post = array(
    'type' => get($post, $i++),
    'aType' => explode('=', get($post, $i++)),
    'sType' => explode('=', get($post, $i++)),
    'minAPrice' => (int) get($post, $i++),
    'maxAPrice' => (int) get($post, $i++),
    'minSPrice' => (int) get($post, $i++),
    'maxSPrice' => (int) get($post, $i++),
    'minASize' => (int) get($post, $i++),
    'maxASize' => (int) get($post, $i++),
    'minSSize' => (int) get($post, $i++),
    'maxSSize' => (int) get($post, $i++),
    'charter' => (int) get($post, $i++),
    'shipyard' => urldecode(get($post, $i++))
);
if ($post['type'] != 'sale' and $post['type'] != 'arenda')
    return 1;
if($post['aType']) {
    $post['aType'] = array_map('ceil', $post['aType']);
    $post['aType'] = array_combine($post['aType'], $post['aType']);
}
if($post['sType']) {
    $post['sType'] = array_map('urldecode', $post['sType']);
    $post['sType'] = array_combine($post['sType'], $post['sType']);
}
$new = array(
    get($post, 'type'),
    implode('=', get($post, 'aType', array())),
    implode('=', array_map('urlencode', get($_POST, 'sType', array()))),
    get($post, 'minAPrice'),
    get($post, 'maxAPrice'),
    get($post, 'minSPrice'),
    get($post, 'maxSPrice'),
    get($post, 'minASize'),
    get($post, 'maxASize'),
    get($post, 'minSSize'),
    get($post, 'maxSSize'),
    get($post, 'charter'),
    urlencode(get($post, 'shipyard'))
);
$new = implode('-', $new);


if (!$info = cmfContent::info('searchYachts'))
    return 404;
list($info, $request, $headerId, $mPath) = $info;

cmfGlobal::set('$post', $post);
$sYachts = $this->run('/sale/new/search/result/yachts/');


$sql = cmfRegister::getSql();
$info = $sql->placeholder("SELECT id FROM ?t WHERE id='shipyards/search/result' LIMIT 0,1", db_main)
        ->fetchAssoc();
$list = $sql->placeholder("SELECT lang, header AS name, content, title, keywords, description FROM ?t WHERE id='shipyards/search/result'", db_main_lang)
        ->fetchAssocAll('lang');
cmfLang::data($info, $list);
$mPath[] = $info['name'];

foreach ($mPath as $key => $value) {
    if (is_string($key)) {
        cmfMenu::add($value, $key);
    } else {
        cmfMenu::add($value);
    }
}
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setH1($info['name']);
cmfSeo::set('title', $info['title']);
cmfSeo::set('keywords', $info['keywords']);
cmfSeo::set('description', $info['description']);

cmfGlobal::set('header-content', $sYachts);
cmfGlobal::set('body', 'rent');
$this->setTeplates('main.yachts.php');
$this->assing('info', $info);
?>