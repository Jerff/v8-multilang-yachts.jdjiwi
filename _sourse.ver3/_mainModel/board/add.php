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
$title = word('Добавить объявление');
cmfMenu::add($title);
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu, $mBMenu);
cmfGlobal::set('body', 'contacts');

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $title);
cmfSeo::set('description', $title);


$user = cmfRegister::getUser();
$user->reset();
$this->assing2('user', $user);

cmfLoad('board/cmfBoardAdd');
$boardAdd = new cmfBoardAdd();
$this->assing('boardAdd', $boardAdd);
$this->assing2('isUser', $boardAdd->get('$isUser'));
$this->assing2('param', $boardAdd->get('$param'));
$this->assing2('config', $boardAdd->getForm(1));
$this->assing2('password', $boardAdd->getForm(2));
$this->assing2('board', $boardAdd->getForm(3));
if ($user->is()) {
    $this->assing2('boardContent', cmfContent::getStatic('Доска объявлений: Добавить объявление (пользовать авторизован)'));
} else {
    $this->assing2('boardContent', cmfContent::getStatic('Доска объявлений: Добавить объявление (пользовать не авторизован)'));
}
?>