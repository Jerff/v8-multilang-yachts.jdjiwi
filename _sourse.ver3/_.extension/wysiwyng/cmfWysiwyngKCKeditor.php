<?php

cmfLoad('wysiwyng/cmfWysiwyngDriver');
set_include_path(get_include_path() . PATH_SEPARATOR . cmfWWW .'library/kckeditor/');
include_once(cmfWWW .'library/kckeditor/fckeditor.php');

class cmfWysiwyngKCKeditor extends cmfWysiwyngDriver {

	static public function html($path, $number, $id, $value, $height=null) {
		$oFCKeditor = new FCKeditor($id) ;
		$oFCKeditor->BasePath	= cmfProjectMain .'library/kckeditor/';
		$oFCKeditor->Value		= $value ;
		if($height) $oFCKeditor->Height = $height;
		$oFCKeditor->ConfigURl		= array('path'=>$path, 'number'=>$number) ;
		return $oFCKeditor->Create() ;
	}

	static public function jsUpdate($id, $value) {
        $value = cmfToJsString($value);
        $js =<<<HTML
FCKeditorAPI.Instances.{$id}.SetHTML('$value');
HTML;
		return $js;
	}

}

?>