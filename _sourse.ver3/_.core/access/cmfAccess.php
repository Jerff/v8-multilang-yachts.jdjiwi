<?php


class cmfAccess {

    static public function noAccess($message='Вам отказано в доступе на просмотр данной страницы') {
        if(cmfAjax::get()) {
            cmfAjax::get()->addAlert($message);
        } else {
            echo $message;
		}
		exit;
	}


	static public function isRead(&$controller) {
        cmfDebug::sqlLogOff();
 		$read = cmfRegister::getSql()->placeholder("SELECT `modul` FROM ?t WHERE `group` ?@", db_access_read, cmfRegister::getAdmin()->getGroup())
						->fetchRowAll(0, 0);
		if(!self::isAccess($read, $controller)) {
			self::noAccess();
		}
		cmfDebug::sqlLogOn();
	}

	static public function isControllerRead(&$controller) {
        self::noAccess('Вам отказано в доступе на чтение данных');
	}

	static public function isWrite(&$controller) {
        cmfDebug::sqlLogOff();
 		$write = cmfRegister::getSql()->placeholder("SELECT `modul` FROM ?t WHERE `group` ?@", db_access_write, cmfRegister::getAdmin()->getGroup())
						->fetchRowAll(0, 0);
		if(!self::isAccess($write, $controller)) {
			self::noAccess('Вам отказано в доступе на изменения данных страницы');
		}
		cmfDebug::sqlLogOn();
	}

	static public function isDelete(&$controller) {
        cmfDebug::sqlLogOff();
 		$write = cmfRegister::getSql()->placeholder("SELECT `modul` FROM ?t WHERE `group` ?@", db_access_delete, cmfRegister::getAdmin()->getGroup())
						->fetchRowAll(0, 0);
		if(!self::isAccess($write, $controller)) {
			self::noAccess('Вам отказано в доступе на удаление данных');
		}
		cmfDebug::sqlLogOn();
	}

	static public function isAccess($read, &$controller) {
		$name = get_class($controller);
 		list($_modul, $_rules, $_controller) = self::getRules();
 		if(!isset($_controller[$name])) return false;
 		$rule = $_controller[$name];
 		if(!$rule) return true;
 		if(!empty($_rules[$rule]['modul'])) {
 			$modul = $_rules[$rule]['modul'];
 			if(isset($read[$modul])) return true;
 			if(isset($read[$modul .'/'. $rule])) return true;
 			return false;
 		}
 		return self::isAccessTree($rule, $controller->getId(), $read, $controller, $_modul, $_rules, $_controller);
	}

	static public function isAccessTree($rule, $id, $read, &$controller, &$_modul, &$_rules, &$_controller) {
 		if(!isset($_rules[$rule]['rules'])) return false;
 		$rules = $_rules[$rule]['rules'];
 		foreach($rules as $name=>$rule) {
	 		$where = get($rule, 'where', array());
 			if($controller->isRule($where)) {
		 		if($id) {

		 		} else {

		 		}
		 		if(!isset($_rules[$name])) return false;
 				 if(!empty($_rules[$name]['modul'])) {
		 			$modul = $_rules[$name]['modul'];
		 			if(isset($read[$modul])) return true;
		 			if(isset($read[$modul .'/'. $rule])) return true;
		 			return false;
		 		}
		 		return self::isAccessTree($rule, $id, $read, $controller, $_modul, $_rules, $_controller);
 			}
 		}
 		return false;
 	}


	static private function getRules() {
        if(!$_rules = cmfCacheAdmin::get('cmfAccess::getRules')) {

	        $_rules = $_controller = $_modul = array();
	        foreach(cmfAccessModul::getRulesFiles() as $f) {
				$xml = new SimpleXMLElement(file_get_contents($f));
				$path = preg_replace('~.*\/((_?\w+)\/rules\.xml)~', '$2', $f) .'/';
				foreach ($xml->modul as $modul) {
					$modulId = (string)$modul->attributes()->id;
					if($modul->rules) {
						foreach ($modul->rules->rule as $rule) {
		                    $ruleId = (string)$rule->attributes()->id;
		                    $_modul[$modulId][$ruleId] = $ruleId;
		                    $_rules[$ruleId]['modul'] = $modulId;
		                    $_rules[$ruleId]['object'] = (string)$rule->attributes()->object===true;
		                    foreach ($rule->elements->element as $element) {
		                    	$controller = str_replace('/', '_', $path . (string)$element .'/controller');
		                    	$_rules[$ruleId]['controller'][] = $controller;
		                    	$_controller[$controller] = $ruleId;
		                    }
		                    if($rule->childs)
		                    foreach ($rule->childs->child as $child) {
		                    	$childId = (string)$child;
		                    	$rule = array('parent'=>(string)$child->attributes()->parentId);
		                    	foreach($child->attributes() as $k=>$v) {
		                    		if($k!='parentId') $rule['where'][$k] = (string)$v;
		                    	}
		                    	$_rules[$childId]['rules'][$ruleId] = $rule;
		                    }
						}
					} else {
						$controller = str_replace('/', '_', $path .'controller');;
						$_controller[$controller] = false;
					}
				}
			}

			$_rules = array($_modul, $_rules, $_controller);
			cmfCacheAdmin::set('cmfAccess::getRules', $_rules, 'access');
		}
		return $_rules;
	}
}

?>