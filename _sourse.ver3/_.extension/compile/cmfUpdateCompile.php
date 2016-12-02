<?php


class cmfUpdateCompile {

	public static function start(){
		$_cahce = cmfUpdateCompileConfig::fileList();
		set_time_limit(0);
		ignore_user_abort(true);

		$compile = new cmfCompilePhp();
		foreach(cmfUpdateCompileConfig::fileList() as $name) if(is_file($name)){
			$content = $compile->compile($name);
			file_put_contents(cmfCompileLib . $name, $content);
		}

		$compile = new cmfCompileFile();
		$compile->compile(cmfUpdateCompileConfig::sourseCss(), false);
		$compile->compile(cmfUpdateCompileConfig::sourseJs(), true);

		ignore_user_abort(false);
	}

}

?>
