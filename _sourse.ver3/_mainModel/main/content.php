<?php

cmfGlobal::set('body', 'info');

$infoId = cmfGlobal::get('$infoId');
$pageId = cmfGlobal::get('$pageId');
if (!$infoId)
    return 404;
$sql = cmfRegister::getSql();
$info = $sql->placeholder("SELECT id, path, parent, pages, request, isUri FROM ?t WHERE id=? AND isVisible='yes'", db_content, $infoId)
        ->fetchAssoc();
if (!$info)
    return 404;
$list = $sql->placeholder("SELECT lang, name, content, title, keywords, description FROM ?t WHERE id=?", db_content_lang, $infoId)
        ->fetchAssocAll('lang');
cmfLang::data($info, $list);


if (!empty($info['path'])) {
    $res = $sql->placeholder("SELECT id, parent, isUri FROM ?t WHERE id ?@ AND visible='yes' ORDER BY level", db_content, cmfString::pathToArray($info['path']))
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name, menu FROM ?t WHERE id ?@", db_content_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        if (empty($row['parent'])) {
            cmfMenu::setSelect('$headerId', $id . 'menu');
        }
        cmfMenu::add(empty($row['menu']) ? $row['name'] : $row['menu'], cmfGetUrl('/info/', array($row['isUri'])));
    }
}
if ($pageId > 1) {
    cmfMenu::add(empty($info['menu']) ? $info['name'] : $info['menu'], cmfGetUrl('/info/', array($info['isUri'])));
} else {
    cmfMenu::add(empty($info['menu']) ? $info['name'] : $info['menu']);
}


$res = $sql->placeholder("SELECT id, image, name, link FROM ?t WHERE page='content' AND parent=? AND visible='yes' ORDER BY main, pos DESC", db_main_slider, $info['id'])
        ->fetchAssocAll('id');
$mSlider = array();
if ($res) {
    $list = $sql->placeholder("SELECT id, lang, name, notice FROM ?t WHERE id ?@ AND page='content' AND parent=?", db_main_slider_lang, array_keys($res), $info['id'])
            ->fetchAssocAll('id', 'lang');
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mSlider[$row['id']] = array(
            'title' => empty($row['notice']) ? htmlspecialchars($row['name']) : '#notice' . $row['id'],
            'alt' => htmlspecialchars($row['name']),
            'link' => $row['link'],
            'notice' => $row['notice'],
            'image' => cmfPathMain . $row['image']
        );
    }
}

cmfContent::setType($info['pages']);
cmfContent::replace($this, $info['content'], $mSlider);
cmfMenu::setRequest($info['request']);
cmfMenu::setSelect('$headerId', $infoId . 'menu');
cmfMenu::setH1($info['name']);
cmfGlobal::set('$infoName', $info['name']);
cmfSeo::set('title', empty($info['description']) ? $info['name'] : $info['title']);
cmfSeo::set('keywords', empty($info['description']) ? $info['name'] : $info['keywords']);
cmfSeo::set('description', empty($info['description']) ? $info['name'] : $info['description']);
$this->assing('info', $info);
?>