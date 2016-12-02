<?php

cmfRegister::getUser()->filterNoUser();


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

$title = word('Восстановление пароля');
cmfMenu::add($title);
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu, $mBMenu);
cmfGlobal::set('body', 'contacts');

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $title);
cmfSeo::set('description', $title);


cmfLoad('user/cmfUserRecoverPassword');
$cmfUserEnter = new cmfUserRecoverPassword();
$this->assing('password', $cmfUserEnter);
$this->assing('form', $cmfUserEnter->getForm());

$this->assing2('enterUrl', cmfGetUrl('/user/enter/'));
$this->assing2('registerUrl', cmfGetUrl('/user/register/'));
$this->assing2('content', cmfContent::getStatic('Личный кабинет: Восстановление пароля'));
?>