<?php

cmfLoad('wysiwyng/cmfWysiwyngDriver');
class cmfWysiwyngDriver {

    //abstract static public function html($path, $number, $id, $value);
    //abstract static public function getJsSetData($id, $value);

	static public function getPath($path, $number) {
		if(!$path or !$number) exit;

		$_map = cmfWysiwyngConfig::getMap();
		if(!isset($_map[$path])) exit;
		$controller = $_map[$path];
		if(is_array($controller)) {
			$path = $controller[0];
			$controller = $controller[1];
		}
		else $path = cmfFilePathWysiwyng . $path .'/';


		$controller = cmfModulLoad($controller);
		cmfAccess::isWrite($controller);

		if(!$controller->getWysiwyngIsRecord($number)) exit;

		if(!$path) exit;
		$path = $path . $number .'/';
		$filePath = cmfWWW . $path;
		$path = '/'. $path;
		return array($path, $filePath);
	}

	static public function addRecord($path, $number) {
        /*if(!$path or !$number) return;
        $path = cmfWWW . $path;
        if(!cmfDir::isDir($path)) {
            if(!cmfDir::newDir($path)) return;
        }
        $path .= $number .'/';
        if(!cmfDir::isDir($path)) {
            if(!cmfDir::newDir($path)) return;
        }*/
	}

	static public function delRecord($path, $number) {
        if(!$path or !$number) return;
        $path = cmfWWW . $path . $number .'/';
        cmfDir::clear($path, true);
	}

	public function getRecordPath($modul) {
		$_map = cmfWysiwyngConfig::getMap();
		while((list($k, $v) = each($_map))) {
            if(is_string($v)) {
            	if($modul===$v) return cmfFilePathWysiwyng . $k .'/';
            } else {
				if($modul===$v[1]) {
					return $v[0];
				}
            }
		}
		return false;
	}

}

?>