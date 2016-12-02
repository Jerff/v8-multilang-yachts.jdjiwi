<?php


abstract class driver_modul_lang_edit extends driver_modul_edit {


	protected function saveStartUri(&$send, $modul, $len, $where=null) {
		if(empty($send['uri'])) {
			$uri = $this->getForm()->handlerElement('uri');
			if(!$uri) {
				list($modul, $name) = $modul;
				$modul = cmfModulLoad($modul);
				$modul->initModul();
				$name = $modul->getForm()->handlerElement($name);
				if($name) $send['uri'] = cmfReformUri($name, $len);
			}
		}
		if(isset($send['uri'])) {
            if($where) {
				$send['uri'] = $this->getDb()->updateIsUrlExistsWhere($send['uri'], $where);
            } else {
            	$send['uri'] = $this->getDb()->updateIsUrlExists($send['uri']);
            }
		}
	}

}

?>
