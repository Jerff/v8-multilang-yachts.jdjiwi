<?php

$user = cmfRegister::getUser();
$user->filterIsUser();
$user->reset();


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
$title = word('Изменить пароль');
cmfMenu::add($title);
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu, $mBMenu);
cmfGlobal::set('body', 'contacts');

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $title);
cmfSeo::set('description', $title);


cmfLoad('user/cmfUserChangePassword');
if ($changePassword = cmfCache::get('register')) {
    list($changePassword, $content) = $changePassword;
} else {

    $changePassword = new cmfUserChangePassword();
    $content = cmfContent::getStatic('Личный кабинет: смена пароля');

    cmfCache::set('register', array($changePassword, $content), 'user');
}


$this->assing('password', $changePassword);
$this->assing('form', $changePassword->getForm());

$this->assing('content', $content);
?>