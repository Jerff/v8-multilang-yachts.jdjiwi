<?php

cmfRegister::getUser()->filterNoUser();
if (isset($_GET['fancybox'])) {
    $this->setTeplates('fancybox.info.php');
}

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

$title = word('Регистрация');
cmfMenu::add($title);
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu, $mBMenu);
cmfGlobal::set('body', 'contacts');

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $title);
cmfSeo::set('description', $title);


cmfLoad('user/cmfUserRegister');
$userRegister = new cmfUserRegister('board-add');
$this->assing('userRegister', $userRegister);
$this->assing('form', $userRegister->getForm(1));
$this->assing('formAll', $userRegister->getForm(2));

$this->assing2('enterUrl', cmfGetUrl('/user/enter/'));
$this->assing2('passwordUrl', cmfGetUrl('/user/password/'));
$this->assing2('content', cmfContent::getStatic('Личный кабинет: Регистрация'));
?>