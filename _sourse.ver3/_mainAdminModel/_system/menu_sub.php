<?php
cmfDebug::sqlLogOff();


list($parent1, $parent2, $parent3, $_menu, $_subMenu) = cmfAdminMenu::getSubMenu();

end($_menu);
$this->assing('end', key($_menu));
reset($_menu);
$this->assing('menu', $_menu);


$this->assing('subMenu', $_subMenu);
$this->assing('subMenuEnd', count($_subMenu)-1);

$this->assing('parent1', $parent1);
$this->assing('parent2', $parent2);
$this->assing('parent3', $parent3);

cmfDebug::sqlLogOn();
?>