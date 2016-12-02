<?php
cmfDebug::sqlLogOff();

$this->assing('_langList', cmfLang::getAdminList());
$this->assing('_menu', cmfAdminMenu::getMenu());
$this->assing('updateUrl', cmfGetAdminCommand('/admin/cache/data/'));
$this->assing('name', cmfRegister::getAdmin()->get('name'));
$this->assing('profilUrl', cmfGetAdminUrl('/admin/profil/'));


cmfDebug::sqlLogOn();
?>