<?php

$infoId = cmfGlobal::get('$infoId');
$boardId = cmfGlobal::get('$param1');
if (!$infoId)
    return 404;

if (!$info = cmfContent::info($infoId, true))
    return 404;
list($info, $request, $headerId, $mPath) = $info;
$mMenu = cmfContent::initSubMenu($info, $infoId, 0, $boardId);
$mBMenu = cmfContent::initBoardMenu($boardId);
foreach ($mPath as $key => $value) {
    if (is_string($key)) {
        cmfMenu::add($value, $key);
    } else {
        cmfMenu::add($value);
    }
}
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu, $mBMenu);
cmfGlobal::set('body', 'yacht');


$sql = cmfRegister::getSql();
$board = $sql->placeholder("SELECT * FROM ?t WHERE id=?", db_board, $boardId)
        ->fetchAssoc();
if (!$board)
    return 404;
$isModer = ($board['visible'] === 'yes' or $board['moder'] === 'yes');
if ($isModer or (cmfRegister::getUser()->getId() == $board['user'])) {
    $status = array();


    if(cmfRegister::getUser()->getId() == $board['user'])
        if ($board['visible'] === 'no' and $board['moder'] === 'no') {
        $status[] = $board['moder'] === 'no' ? word('Не модерировано') : word('Модерировано');
    } else {
        if ($board['visible'] === 'no') {
            $status[] = word('Отключен');
        }
        $status[] = $board['moder'] === 'no' ? word('Не модерировано') : word('Модерировано');
    }
    $board['status'] = implode(', ', $status);

    if ($isModer and cmfRegister::getUser()->getId() != $board['user'] and !empty($board['data'])) {
//        $board = cmfString::unserialize($board['data']);
    } elseif(!$isModer) {
        $this->assing2('content', cmfContent::getStatic('Доска объявлений: Объявление не опубликовано'));
    }

    $mFoto = array();
    if (!empty($board['image'])) {
        $images = cmfString::unserialize($board['image']);
        $i = 0;
        foreach ($images as $value) {
            if(empty($value)) continue;
            if ($i++) {
                $mFoto[$i] = array(
                    'title' => htmlspecialchars(empty($board['name']) ? $title . ' ' . $i : $board['name']),
                    'main' => cmfBaseImg . cmfPathBoard . $value,
                    'small' => cmfImage::preview(cmfBaseImg . cmfPathBoard . $value, 250, 130)
                );
                list(,,, $mFoto[$i]['width']) = getimagesize($mFoto[$i]['small']);
            } else {
                $board['image'] = cmfBaseImg . cmfPathBoard . $value;
                $board['small'] = cmfImage::preview($board['image'], 620, 620);
                list(,,, $board['width']) = getimagesize($board['small']);
                $board['title'] = htmlspecialchars($board['name']);
            }
        }
    }
    cmfLoad('catalog/function');
    $board['price'] = cmfPrice::view2($board['price'], $board['currency']);
    $board['notice'] = nl2br($board['notice']);
    cmfMenu::add($board['name']);

    $sql = cmfRegister::getSql();
    $notice = array();
    if (!empty($board['param'])) {
        $res = $sql->placeholder("SELECT i.`id`, l.`name`, i.`value` FROM ?t i LEFT JOIN ?t l ON(i.id=l.id) WHERE i.`id` IN(SELECT param FROM ?t WHERE `group`='board' AND visible='yes') ORDER BY  l.`name`", db_param, db_param_lang, db_param_group_notice)
                ->fetchAssocAll('id');
        $param = cmfString::unserialize($board['param']);
        foreach ($res as $k => $v) {
            $mVParam = cmfString::unserialize($v['value']);
            $key = 'paramKey' . $k;
            $value = 'paramValue' . $k;
            if (!empty($mVParam[$param[$key]])) {
                $notice[$v['name']] = word($mVParam[$param[$key]]);
            } elseif (!empty($param[$value])) {
                $notice[$v['name']] = $param[$value];
            }
        }
    }

    $board['contact'] = array_map('trim', explode(PHP_EOL, $board['contact']));
    cmfSeo::set('title', $board['name']);
    cmfSeo::set('keywords', $board['name']);
    cmfSeo::set('description', $board['name']);
    $this->assing('notice', $notice);
    $this->assing('board', $board);
    $this->assing('mFoto', $mFoto);
} else {

    header("HTTP/1.0 404 Not Found");
    $title = word('Объявление не опубликовано');
    cmfMenu::add($title);
    cmfSeo::set('title', $title);
    cmfSeo::set('keywords', $title);
    cmfSeo::set('description', $title);

    $this->assing2('isModer', true);
    cmfGlobal::set('body', 'info');
    $this->assing2('content', cmfContent::getStatic('Доска объявлений: Объявление не опубликовано'));
}
?>