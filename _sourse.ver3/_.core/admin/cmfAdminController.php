<?php


class cmfAdminController {


	private $controller = array();

	function __destruct() {
		$modulAll = $this->getController();
		reset($modulAll);
		while(list(, $modul) = each($modulAll))
			$modul->free();
	}

	public function &load($controller, $id=null) {
		if(!class_exists($controller)) {
			cmfModul($controller);
		}
		$this->controller[$controller] = new $controller($id);
		return $this->controller[$controller];
	}

	public function &getController() {
		return $this->controller;
	}


	public function run() {
		$controllerAll = $this->getController();

		$r = cmfRegister::getRequest();
		$r->setBak();
		if($r->isPost('isRunController')) {
			cmfAjax::start();
			$name = (string)$r->getPost('ajaxName');
			if(isset($controllerAll[$name])) {
				$arg = (string)$r->getPost('ajaxArg');
				$arg = json_decode($arg);
				if($r->isPost('save')) {
					$arg[] = 'save';
				}

				cmfAccess::isRead($controllerAll[$name]);
				$controllerAll[$name]->callFunction($arg);
 				$r->resetGet();
			}
			if(!cmfCommand::is('$isNewView')) exit;
		}

		reset($controllerAll);
		while(list($name, $controller) = each($controllerAll)) {
			cmfAccess::isRead($controller);
			$controller->run();
		}
	}

}

?>
