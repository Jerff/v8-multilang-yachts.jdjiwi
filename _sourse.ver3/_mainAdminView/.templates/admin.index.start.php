<?

$r = cmfAjax::get();

$menuStart = $this->run('/admin/menu/start/');
$menuSub = $this->run('/admin/menu/sub/');
$menuEnd1 = $this->run('/admin/menu/end1/');
$menuEnd2 = $this->run('/admin/menu/end2/');

$r->loadHTML('mainIndex', $menuStart . $menuSub . $content . $menuEnd1 . $menuEnd2);


?>