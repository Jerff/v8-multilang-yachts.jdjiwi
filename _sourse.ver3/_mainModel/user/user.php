<?php

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
$title = word('Личный кабинет');
cmfMenu::add($title);
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu, $mBMenu);
cmfGlobal::set('body', 'contacts');

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $title);
cmfSeo::set('description', $title);


$this->assing2('user', $user);

$userId = $user->getId();
$sql = cmfRegister::getSql();
$this->assing2('content', cmfContent::getStatic('Личный кабинет: Информация'));
$this->assing2('infoUrl', cmfGetUrl('/user/info/'));
$this->assing2('boardUrl', cmfGetUrl('/board/add/'));
$this->assing2('passwordUrl', cmfGetUrl('/user/info/password/'));
$this->assing2('exitUrl', cmfGetUrl('/user/exit/'));
?>