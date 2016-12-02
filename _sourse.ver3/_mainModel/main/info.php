<?php

$infoId = cmfGlobal::get('$infoId');
$pageId = cmfGlobal::get('$pageId');
if (!$infoId)
    return 404;


if (!$info = cmfContent::info($infoId, $pageId > 1))
    return 404;
list($info, $request, $headerId, $mPath) = $info;
foreach ($mPath as $key => $value) {
    if (is_string($key)) {
        cmfMenu::add($value, $key);
    } else {
        cmfMenu::add($value);
    }
}
if($info['isUri']==='prodaga-yacht/search') {
    cmfRedirectSeo(cmfGetUrl('/index/'));
}
cmfMenu::setRequest($infoId);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu(
        cmfContent::initSubMenu($info, $infoId),
        $info['pages']==='board' ? cmfContent::initBoardMenu() : null
        );
cmfGlobal::set('body', 'info');


$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, image, name, link FROM ?t WHERE page='info' AND parent=? AND visible='yes' ORDER BY main, pos DESC", db_main_slider, $info['id'])
        ->fetchAssocAll('id');
$mSlider = array();
if ($res) {
    $list = $sql->placeholder("SELECT id, lang, name, notice FROM ?t WHERE id ?@ AND page='info' AND parent=?", db_main_slider_lang, array_keys($res), $info['id'])
            ->fetchAssocAll('id', 'lang');
    $i = 0;
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mSlider[$row['id']] = array(
            'title' => empty($row['notice']) ? htmlspecialchars($row['name']) : '#notice' . $row['id'],
            'alt' => htmlspecialchars($row['name']),
            'link' => $row['link'],
            'notice' => $row['notice'],
//            'image' => cmfPathMain . $row['image'],
			'image' => cmfImage::preview(cmfBaseImg . cmfPathMain . $row['image'], 660, 296,$row['name'],'i-s', $row['id']),
        );
    }
}

cmfContent::setType($info['pages']);
cmfMenu::setH1($info['name']);
cmfContent::replace($this, $info['content'], $mSlider);
cmfGlobal::set('$infoName', $info['name']);
cmfSeo::set('title', empty($info['description']) ? $info['name'] : $info['title']);
cmfSeo::set('keywords', empty($info['description']) ? $info['name'] : $info['keywords']);
cmfSeo::set('description', empty($info['description']) ? $info['name'] : $info['description']);
$this->assing('info', $info);
?>