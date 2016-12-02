<?php

//if($_menu = cmfCache::get('menu')) {
//    list($_menu, $_root) = $_menu;
//} else {
//	list($_menu, $_root) = cmfMenu::getLeft();
//	cmfCache::set('menu', array($_menu, $_root), 'menu');
//}
$this->assing('_menu', $_menu);
$this->assing('_root', $_root);

?>