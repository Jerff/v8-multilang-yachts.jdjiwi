<?php

$infoId = cmfGlobal::get('$infoId');
$typeId = cmfGlobal::get('$param1');
if (!$infoId or !$typeId)
    return 404;


if (!$info = cmfContent::info($infoId))
    return 404;
list($info, $request, $headerId, $mPath) = $info;
$mMenu = cmfContent::initSubMenu($info, $infoId);
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

$sql = cmfRegister::getSql();
$type = $sql->placeholder("SELECT id, uri FROM ?t WHERE id=? AND visible='yes'", db_brokerage_type, $typeId)
        ->fetchAssoc();
if (!$type)
    return 404;
$list = $sql->placeholder("SELECT lang, name, menu, content, title, keywords, description FROM ?t WHERE id=?", db_brokerage_type_lang, $typeId)
        ->fetchAssocAll('lang');
cmfLang::data($type, $list);
cmfMenu::add($type['name']);


cmfGlobal::set('$typeUri', $info['isUri'] . '/' . $type['uri']);
cmfGlobal::set('header-content', $this->run('/brokerage/type/yachts/'));

cmfMenu::setH1($type['name']);
cmfSeo::set('title', $type['title']);
cmfSeo::set('keywords', $type['keywords']);
cmfSeo::set('description', $type['description']);
cmfGlobal::set('body', 'rent');
$this->assing('type', $type);
?>