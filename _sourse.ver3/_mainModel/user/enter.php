<?php

cmfRegister::getUser()->filterNoUser();
if (isset($_GET['fancybox'])) {
    $this->setTeplates('fancybox.info.php');
    cmfDebug::destroy();
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
$title = word('Личный кабинет');
cmfMenu::add($title);
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu, $mBMenu);
cmfGlobal::set('body', 'contacts');

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $title);
cmfSeo::set('description', $title);


cmfLoad('user/cmfUserEnter');
$userEnter = new cmfUserEnter();
$this->assing('userEnter', $userEnter);
$this->assing('form', $userEnter->getForm());
$this->assing2('registerdUrl', cmfGetUrl('/user/register/'));
$this->assing2('passwordUrl', cmfGetUrl('/user/password/'));
?>