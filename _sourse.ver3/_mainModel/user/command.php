<?php

$command = word('Неправильная команда');
$name = word('Личный кабинет');

cmfUrlParam::start(cmfPages::getParam(1));
switch (cmfUrlParam::get('command')) {
    case 'recoverPassword':
        switch (cmfUrlParam::get('action')) {
            case 'error':
                $command = word('Неправильная почта или код подтверждения');
                break;

            case 'ok':
                $command = word('Пароль изменен');
                break;

            default:
                cmfLoad('user/cmfUserRecoverPassword');
                $cmfUserRecoverPassword = new cmfUserRecoverPassword();
                $cmfUserRecoverPassword->run1ok(cmfUrlParam::get('email'), cmfUrlParam::get('cod'));
        }
        break;

    case 'userRegister':
        switch (cmfUrlParam::get('action')) {
            case 'error':
                $command = word('Неправильный аккаунт или код подтверждения');
                break;

            case 'ok':
                $command = word('Активация прошла успешна');
                break;

            default:
                cmfLoad('user/cmfUserRegister');
                $cmfUserRegister = new cmfUserRegister();
                $cmfUserRegister->userActivate(cmfUrlParam::get('user'), cmfUrlParam::get('cod'));
        }
        break;
}

cmfSeo::set('command', $name);


if (!$info = cmfContent::info(array('pages' => 'board'), true))
    return 404;
list($info, $request, $headerId, $mPath) = $info;
$mMenu = cmfContent::initSubMenu($info, $infoId = $info['id']);

foreach ($mPath as $key => $value) {
    if (is_string($key)) {
        cmfMenu::add($value, $key);
    } else {
        cmfMenu::add($value);
    }
}
cmfMenu::add(word('Личный кабинет'), cmfGetUrl('/user/'));

$title = $command;
cmfMenu::add($title);
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu);
cmfGlobal::set('body', 'info');

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $title);
cmfSeo::set('description', $title);


$this->assing('name', $name);
$this->assing('content', $command);
?>