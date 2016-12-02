<?php

cmfLoadResponse();
cmfLoad('JsHttpRequest/JsHttpRequest');
class cmfAjax {

	static private $is = null;
	static private $r = false;

	static public function is() {
		return self::$is;
	}

	static public function start() {
		if(!self::$is) {
			$JsHttpRequest = new JsHttpRequest();
			cmfStripSlashesPost();
            if(!empty($_POST['lang'])) {
                cmfLang::setId($_POST['lang']);
            }
			self::$is = true;
			self::$r = new cmfAjaxResponse();
		}
		return self::$r;
	}

	static public function get() {
		return self::$r;
	}

	static public function isAjax() {
		if(is_null(self::$is)) {
			if(cmfRegister::getRequest()->isGet('isAjax')) {
				cmfAjax::start();
			}
			cmfRegister::getRequest()->unsetGet('isAjax');
		}
		return self::$is;
	}

	static public function getUrl() {
		return (string)cmfRegister::getRequest()->getPost('ajaxUrl');
	}
	static public function isCommand($command=null) {
		if($command) {
			return cmfRegister::getRequest()->getPost('ajaxCommand')===$command;
		} else {
            return cmfRegister::getRequest()->isPost('ajaxCommand');
		}
	}

}

?>