<?

if(cmfAjax::is()) {
	cmfAjax::get()->loadHTML('mainIndex', $content);
}

?>

<?=$this->run('/admin/header/') ?>

<?=$content ?>

<?=$this->run('/admin/footer/') ?>