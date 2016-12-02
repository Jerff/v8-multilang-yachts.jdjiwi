<?php

$pageId = cmfPages::getParam(1);
if (empty($pageId))
    $pageId = 1;

$user = cmfRegister::getUser();
$user->filterIsUser();
$user->reset();
//$user->disable();

if (!$info = cmfContent::info(array('pages' => 'board'), true))
    return 404;
list($info, $request, $headerId, $mPath) = $info;
$mMenu = cmfContent::initSubMenu($info, $infoId = $info['id']);
$mBMenu = cmfContent::initBoardMenu();

foreach ($mPath as $key => $value) {
    if (is_string($key)) {
        cmfMenu::add($value, $key);
    } else {
        cmfMenu::add($value);
    }
}
cmfMenu::add(word('Личный кабинет'), cmfGetUrl('/user/'));
$title = word('Все объявления');
cmfMenu::add($title);
cmfMenu::setH1($title);
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu, $mBMenu);

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $title);
cmfSeo::set('description', $title);

cmfGlobal::set('$pageId', $pageId);
cmfGlobal::set('header-content', $this->run('/user/board/yachts/'));
return 1;


$sql = cmfRegister::getSql();
$limit = cmfConfig::get('site', 'boardLimit');
$offset = ($pageId - 1) * $limit;
if ($offset > 3000)
    return 404;
$res = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id, date, name, notice, visible, moder, register FROM ?t WHERE user=? ORDER BY `main`, date DESC LIMIT ?i, ?i", db_board, $user->id, $offset, $limit)
        ->fetchAssocAll('id');
if (!empty($res)) {
    $count = $sql->getFoundRows();
    $mBoard = array();
    foreach ($res as $id => $row) {
        $status = array();
        if ($row['visible'] === 'no' and $row['register'] === 'no' and $row['moder'] === 'no') {
            $status[] = $row['moder'] === 'no' ? word('Не модерировано') : word('Модерировано');
        } else {
            if ($row['visible'] === 'no') {
                $status[] = word('Отключен');
            }
            if ($row['register'] === 'no') {
                $status[] = word('Не зарегистрировано');
            }
            $status[] = $row['moder'] === 'no' ? word('Не модерировано') : word('Модерировано');
        }

        $mBoard[$id] = array('date' => date('d.m.Y', strtotime($row['date'])),
            'header' => $row['name'],
            'status' => implode(', ', $status),
            'notice' => $row['notice'],
            'url' => cmfGetUrl('/board/item/', array($id)));
    }

    $mPageUrl = cmfPagination::generate($pageId, $count, $limit, cmfConfig::get('site', 'boardPages'), create_function('&$page, $k, $v', '
    	$page[$k]["url"] = $k==1 ? cmfGetUrl("/board/") : cmfGetUrl("/board/page/", array($k));'));
    $this->assing('mBoard', $mBoard);
    if ($mPageUrl)
        $this->assing('mPageUrl', $mPageUrl);
} else {
    return 404;
}
?>