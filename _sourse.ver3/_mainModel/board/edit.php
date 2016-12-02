<?php

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

$boardId = cmfPages::getParam(1);
$sql = cmfRegister::getSql();
$board = $sql->placeholder("SELECT * FROM ?t WHERE id=?", db_board, $boardId)
        ->fetchAssoc();
if (!$board)
    return 404;

$user = cmfRegister::getUser();
$user->reset();
$isModer = ($board['visible'] === 'yes' or $board['moder'] === 'yes' or $board['register'] === 'yes');
if ($isModer or $user->getId() == $board['user']) {
    cmfMenu::add($board['name'], cmfGetUrl('/board/item/', array($boardId)));
    $title = word('Редактировать объявление');
} else {
    $title = word('Нет доступа');
}
cmfMenu::add($title);
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu, $mBMenu);
cmfGlobal::set('body', 'contacts');
$this->assing('title', $title);

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $title);
cmfSeo::set('description', $title);

if ($user->is()) {
    if ($user->getId() == $board['user']) {
        cmfLoad('board/cmfBoardEdit');
        $boardEdit = new cmfBoardEdit();
        $boardEdit->loadData($board);
        $this->assing('boardEdit', $boardEdit);
        $this->assing2('boardId', $boardId);
        $this->assing2('param', $boardEdit->get('$param'));
        $this->assing2('board', $boardEdit->getForm());

        $content = cmfContent::getStatic('Доска объявлений: Редактировать объявление (пользовать авторизован)');
    } else {
        $content = cmfContent::getStatic('Доска объявлений: Редактировать объявление (пользовать авторизован - чужое объявление)');
    }
} else {
    $content = cmfContent::getStatic('Доска объявлений: Редактировать объявление (пользовать не авторизован)');
}
$this->assing2('content', str_replace('%boardId%', $boardId, $content));
?>