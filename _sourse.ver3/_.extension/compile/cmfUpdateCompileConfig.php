<?php


class cmfUpdateCompileConfig {

	//cmfCompilePhp
	public static function fileList() {
		return array(	'.includeAdmin.php',
						'.includeMain.php',
						'.includeCron.php',
						'.includeController.php');
	}

	public static function includePath() {
		return array(	cmfSourse,
						cmfSourse .'_.config/',
						cmfSourse .'_.core/',
						cmfSourse .'_.extension/',
						cmfSourse .'_.plugin/',
						cmfSourse .'_library/');
	}


	//cmfCompileFile
	public static function soursePath() {
		return cmfWWW .'sourseCompile/';
	}

	public static function sourseCss() {
		return array('cssCompile.css', array(cmfWWW .'sourseCss/',
		                                     cmfWWW .'css/'));
	}

	public static function sourseJs() {
		return array('jsCompile.js', array(cmfWWW .'sourseJs/',
		                                   cmfWWW .'js/plagin.first/',
		                                   cmfWWW .'js/plagin/'));
	}

}

?>
