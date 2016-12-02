<?php

$search = $this->run('/menu/search/');
$this->assing2('boardAdd', cmfGetUrl('/board/add/'));
$this->assing2('mMenu', cmfMenu::getRMenu());
$this->assing2('mBMenu', cmfMenu::getBMenu());
$this->assing('search', $search);
$this->assing2('isSearchYachts', cmfGlobal::is('searchYachts'));
$this->assing2('networkR', cmfConfig::get('site', 'blogNetwork'));
?>