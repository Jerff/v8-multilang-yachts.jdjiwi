<?php


class cmfAccessModul {


	static public function getRulesFiles() {
        $file = array();
        foreach(cmfDir::getListDir(cmfAdminModel) as $f) {
            if(is_file(cmfAdminModel . $f .'rules.xml')) {
            	$file[] = cmfAdminModel . $f .'rules.xml';
            }
        }
        return $file;
	}


	// выборка для постоения списков доступа
	static public function getListModul() {
        $_modul = array();
        foreach(self::getRulesFiles() as $f) {
			$xml = new SimpleXMLElement(file_get_contents($f));
			$pack = (string)$xml->attributes()->package;
			if($pack) {
				$_modul['pack_'. cmfString::translate($pack)] = array('name'=>$pack);
			}
			foreach ($xml->modul as $modul) {
				if($modul->attributes()->authorization=='false') continue;
				if($modul->attributes()->child=='true') continue;
				$isChild = false;
				$modulName = (string)$modul->attributes()->name;
				$modulId = (string)$modul->attributes()->id;
				$_modul[$modulId] = array(	'name'=>$modulName,
											'pack'=>$pack);
				foreach($modul->rules->rule as $rule) {
					$isChild = true;
					$id = cmfString::translate((string)$rule->attributes()->id);
					$name = (string)$rule->attributes()->name;
					if($name) {
						$_modul[$modulId]['child'][$id] = array(	'name'=>$name,
																	'object'=>$rule->attributes()->object=='true');
					}
				}
			}
        }
        uasort($_modul, array('cmfAccessModul', 'usortCmp'));

        $_new = array();
        while(list($k, $v)=each($_modul)) {
			$_new[$k]['name'] = $v['name'];
			$_new[$k]['isPack'] = !isset($v['pack']);//!isset($v['pack']);
			if(isset($v['child'])) {
				while(list($k1, $v2)=each($v['child'])) {
                    $v2['child'] = true;
                    $v2['pack'] = false;
                    $_new["$k/$k1"] = $v2;
				}
			}
        }
		return $_new;
	}

	static private function usortCmp($a, $b) {
		$a = get($a, 'pack') . $a['name'];
		$b = get($b, 'pack') . $b['name'];
		return strnatcasecmp($a, $b);
	}


	// выборка для модулей для меню системы
	static public function getListModulMenu() {
        $_modul = array();
        foreach(self::getRulesFiles() as $f) {
			$xml = new SimpleXMLElement(file_get_contents($f));
			$pack = (string)$xml->attributes()->package;
			if($pack) {
				$_modul['pack_'. cmfString::translate($pack)] = array(	'name'=>$pack,
																'isPack'=>true);
			}
			foreach ($xml->modul as $modul) {
				if($modul->attributes()->authorization=='false') continue;
				if($modul->attributes()->child=='true') continue;
				$isChild = false;
				$modulName = (string)$modul->attributes()->name;
				$modulId = (string)$modul->attributes()->id;
				$_modul[$modulId] = array(	'name'=>$modulName,
											'pack'=>$pack);
			}
        }
        uasort($_modul, array('cmfAccessModul', 'usortCmp'));
        return $_modul;
	}


	// выборка для модулей для меню системы
	static public function getListMenu($id) {
		$_menu = array();
        $modulName = false;
        foreach(self::getRulesFiles() as $f) {
			$xml = new SimpleXMLElement(file_get_contents($f));
			foreach ($xml->modul as $modul) {
				if($modul->attributes()->authorization=='false') continue;
				if($modul->attributes()->child=='true') continue;

				$name = (string)$modul->attributes()->name;
				$modulId = (string)$modul->attributes()->id;
				if($id===$modulId) {
					$modulName = $name;
                    if($modul->menuList) {
						foreach($modul->menuList->menu as $menu) {
							$name = (string)$menu->attributes()->name;
							if(!$name) $name = (string)$menu->link->attributes()->menu;
							$id = (string)$menu->attributes()->id;
							$_menu[$id] = $name;
						}
					}
					break;
				}

			}
        }
        asort($_menu);
        return array($_menu, $modulName);
	}


	// вернуть список модулей и страниц к ним
	static public function getListPages() {
		if($_page = cmfCacheAdmin::get('cmfAccessModul::getListPages')) return $_page;

		$_page = array();
        foreach(self::getRulesFiles() as $f) {
			$xml = new SimpleXMLElement(file_get_contents($f));
			foreach ($xml->modul as $modul) {
				if($modul->attributes()->authorization=='false') continue;
				if($modul->attributes()->child=='true') continue;

				$modulId = (string)$modul->attributes()->id;
				if($modul->menuList) {
					foreach($modul->menuList->menu as $menu) {
						$id = (string)$menu->attributes()->id;
						$_menu[$modulId][$id] = (string)$menu->link->attributes()->name;
					}
				} else {
					$_menu[$modulId] = (bool)$modul->menu->link ? (string)$modul->menu->link->attributes()->name : false;
				}
			}
        }

		cmfCacheAdmin::set('cmfAccessModul::getListPages', $_menu, 'access');
        return $_menu;
	}

	static public function getModulOfPage($page) {
		if($_modul = cmfCacheAdmin::get('cmfAccessModul::getModulOfPage'))  {
			list($_modul, $_menu) = $_modul;
		} else {
			$_modul = $_menu = array();
	        foreach(self::getRulesFiles() as $f) {
				$xml = new SimpleXMLElement(file_get_contents($f));
				foreach ($xml->modul as $modul) {
					if($modul->attributes()->authorization=='false') continue;
					if($modul->attributes()->child=='true') continue;

					$modulId = (string)$modul->attributes()->id;
					if($modul->menuList) {
						foreach($modul->menuList->menu as $menu) {
							$id = (string)$menu->attributes()->id;
							foreach($menu->link as $link) {
								$name = (string)$link->attributes()->name;
								$_modul[$name] = array($modulId, $id);
								$_menu[$modulId][$id][$name] = array(	'menu'=>(string)$link->attributes()->menu,
																		'select'=>(string)$link->attributes()->select ? explode(',', (string)$link->attributes()->select) : array(),
																		'submenu'=>(string)$link->attributes()->submenu);
							}

						}
					} else {
						if($modul->menu->link)
						foreach($modul->menu->link as $link) {
							$name = (string)$link->attributes()->name;
							$_modul[$name] = array($modulId, '');
							$_menu[$modulId][$name] = array('menu'=>(string)$link->attributes()->menu,
															'select'=>(string)$link->attributes()->select ? explode(',', (string)$link->attributes()->select) : array(),
															'submenu'=>(string)$link->attributes()->submenu);
						}
					}
				}
	        }

			cmfCacheAdmin::set('cmfAccessModul::getModulOfPage', array($_modul, $_menu), 'access');

		}
		if(!isset($_modul[$page])) return false;

		list($modul, $modulMenu) = $_modul[$page];
		if($modulMenu) {
			$_menu = $_menu[$modul][$modulMenu];
		} else {
			$_menu = $_menu[$modul];
		}

		return array($modul, $modulMenu, $_menu);
	}


	static public function getListPageOfModul($_modul) {
		$_pages = array();
        foreach(self::getRulesFiles() as $f) {
			$xml = new SimpleXMLElement(file_get_contents($f));
			$isSys = preg_match('~.*(_\w+\/\w+.xml)$~i', $f);
			foreach ($xml->modul as $modul) {
				$modulId = (string)$modul->attributes()->id;
				if($modul->attributes()->authorization!='false')
					if(!isset($_modul[$modulId])) continue;

				foreach($modul->pages->page as $page) {
					$name = (string)$page->attributes()->name;
					$url = (string)$page->attributes()->url;
					if(!$url) {
						$url = mb_substr($name, mb_strlen('/admin'));
					}
					$path = (string)$page->attributes()->path;
					if(!$path) {
						$path = mb_substr($name, mb_strlen('/admin/'));
                        if($isSys) $path = '_'. $path;
                        $subpath = (string)$page->attributes()->subpath;
                        if($subpath) {
                        	$path .= $subpath .'/';
                        }
                        $path .= 'main';
					} else {
                        $file = (string)$page->attributes()->file;
                        if(!$file) $file = '/main';
                        $path .= $file;
					}
					$_pages[$name] = array(	'url'=>$url,
											'preg'=>(string)$page->attributes()->preg,
											'system'=>(string)$page->attributes()->system=='true',
											'path'=>$path,
											'template'=>(string)$page->attributes()->template);
				}
			}
        }
        return $_pages;
	}

}

?>