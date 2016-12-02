<?

$r = cmfAjax::get();

$menuSub = $this->run('/admin/menu/sub/');
$menuEnd1 = $this->run('/admin/menu/end1/');

$r->loadHTML('mainContent', $menuSub . $content . $menuEnd1);

?>