<?php


class cmfFile {

	const mode  = 0777;

	// upload file
	static function upload($folder, $upload, $key=null) {
		$file = cmfString::translate($key ? $upload['name'][$key] : $upload['name']);
		$name = $file = preg_replace('~[^a-z0-9\-\_\.]~', '', $file);
		$prefix = rand(0, 50) .'/' . rand(0, 50) .'/';
		$folder .= $prefix;
		if(!is_dir($folder)) {
            if(!cmfDir::mkdir($folder)) {
				new cmfException('file_upload.not create folder', $folder);
			}
		}
		if(!is_writable($folder)) {
			new cmfException('file_upload.not write folder', $folder);
		}
		while(file_exists($folder . $name)) {
			if(strpos($file, '.')) {
                $name = preg_replace('`(.*)\.([^.]*)$`', '$1.'. rand(0, 9999) .'.$2', $file);
			} else {
				$name = $file . rand(0, 9999);
			}
		}
		if(!move_uploaded_file($key ? $upload['tmp_name'][$key] : $upload['tmp_name'], $folder . $name)) return null;
		self::chmod($folder . $name);
		return $prefix . $name;
	}

	static function copy($file, $newFile) {
		$dir = dirname($newFile);
		if(!cmfDir::isDir($dir)) {
			if(!cmfDir::mkdir($dir)) {
				new cmfException('file copy.not create folder', $dir);
			}
		}
		$name = $newFile;
		while(file_exists($name)) {
			if(strpos($file, '.')) {
                $name = preg_replace('`(.*)\.([^.]*)$`', '$1.'. rand(0, 9999) .'.$2', $newFile);
			} else {
				$name = $newFile . rand(0, 9999);
			}
		}
		$res = copy($file, $name);
		self::chmod($name);
		return $res ? $name : false;
	}

	static function rename($file, $newFile) {
		return rename($file, $newFile);
	}

	static public function chmod($dir, $mode=self::mode) {
		return chmod($dir, $mode);
	}

	static public function unlink($file) {
		if(is_file($file)) {
			unlink($file);
		}
	}

}

?>
