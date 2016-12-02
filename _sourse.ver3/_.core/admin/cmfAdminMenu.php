<?php


class cmfAdminMenu {

	static public function &getMenu() {
		if($_menu = cmfCacheAdmin::getMenu('menu')) return $_menu;
		$group = cmfRegister::getAdmin()->getGroup();

		$main = cmfPages::getMain();
		$sql = cmfRegister::getSql();

		$read = $sql->placeholder("SELECT p.id FROM ?t p LEFT JOIN ?t a ON(IF(p.modulMenu IS NULL, p.modul, CONCAT(p.modul, '/', p.modulMenu ))=a.modul OR p.modul = a.modul) WHERE p.modul IS NOT NULL AND a.group ?@ AND p.type IN('list') AND p.visible='yes'", db_pages_admin, db_access_read, $group)
						->fetchRowAll(0, 0);
		$pages = cmfAccessModul::getListPages();

		$res = $sql->placeholder("SELECT id, parent, name, type, modul, modulMenu, isView FROM ?t WHERE visible='yes' AND type IN('tree', 'list') ORDER BY pos", db_pages_admin);
		$_menu = array();
		while($row = $res->fetchAssoc()) {
			$parent = $row['parent'];
			$id = $row['id'];
			$_menu[$parent][$id]['name'] = $row['name'];
			if($row['type']==='list') {
				if(!isset($read[$id])) continue;
				if($row['modulMenu'] and isset($pages[$row['modul']]) and is_array($pages[$row['modul']])) {
					$url = get2($pages, $row['modul'], $row['modulMenu']);
				} else {
					$url = get($pages, $row['modul']);
				}
				$_menu[$parent][$id]['url'] = cmfGetAdminUrl($url);
				if($main===$url) $_menu[$parent][$id]['sel'] = true;
			} else {
			    if($row['isView']==='yes') $_menu[$parent][$id]['view'] = true;
			}
		}
		$res->free();

		$level1 = 0;
		self::treeMenu($_menu, $level1);

		cmfCacheAdmin::setMenu('menu', $_menu);
		return $_menu;
	}
	static private function treeMenu(&$_menu, $level1, $level2=0, $parent=0) {
		$level2++;
		$_unset = array();
		$sel = false;
		foreach($_menu[$parent] as $key=>&$value) {
			if($isPages = isset($_menu[$parent][$key]['url'])) {
				$value['url'] = $_menu[$parent][$key]['url'];
				if(isset($_menu[$parent][$key]['sel'])) {
					$value['sel'] = $sel = $level1 = $level2;
				}
			}
			if($isMenu = isset($_menu[$key])) {
				list($sel_k, $unset_k) = self::treeMenu($_menu, $level1, $level2, $key);
				if($sel_k) {
					$value['sel'] = $sel = $level1;
				}
				$isMenu = $unset_k;
				if($isMenu and !$isPages) {
					$child = reset($_menu[$key]);
					$value['url'] = $child['url'];
				}
			}
			if(!$isMenu && !$isPages) $_unset[] = $key;
		}
		foreach($_unset as $key) {
			unset($_menu[$parent][$key]);
		}
		return array($sel, (bool)$_menu[$parent]);
	}



	static private $subMenuId = false;
	static public function setSubMenuId($id) {
		self::$subMenuId = $id;
		cmfRegister::getRequest()->setGet('parentId', $id);
	}
	static public function getSubMenuId() {
		$str = self::$subMenuId ? self::$subMenuId : cmfRegister::getRequest()->getGet('parentId');
		return is_string($str) ? urldecode($str) : $str;
	}


	static private $subMenu = array();
	static public function setUserMenu($page, $sel=false) {
		self::$subMenu[$page] = $sel;
	}
	static private function isUserMenu($page) {
		foreach($page as $p) {
            if(isset(self::$subMenu[$p])) return true;
		}
		return false;
	}

	static private $selectMenu = false;
	static public function selectUserMenu($page) {
		self::$selectMenu = $page;
	}
	static private function isSelectUserMenu($page) {
		return self::$selectMenu===$page;
	}
	static private function subMenuCache() {
		return serialize(self::$subMenu);
	}

	static public function &getSubMenu() {
		if($_menu = cmfCacheAdmin::getMenu('subMenu'. self::subMenuCache())) return $_menu;
		$_menu = $_subMenu = array();
		$_path = array(0, 0, 0);


		$main = cmfPages::getMain();
		$group = cmfRegister::getAdmin()->getGroup();
		$res = cmfAccessModul::getModulOfPage($main);
		if($res) {
            list($modul, $modulMenu, $_modulMenu) = $res;
			$sql = cmfRegister::getSql();
			if($modulMenu) {
				list($parent) = $sql->placeholder("SELECT id FROM ?t WHERE modul=? AND modulMenu=? AND type='list'", db_pages_admin, $modul, $modulMenu)
										->fetchRow();
			} else {
				list($parent) = $sql->placeholder("SELECT id FROM ?t WHERE modul=? AND type='list'", db_pages_admin, $modul)
										->fetchRow();
			}
			if($parent) {

				$menuId = $selectId = $isSub = false;
				foreach($_modulMenu as $k=>$v) {
						if(!empty($v['menu'])) {

						$menuId = $k;
						$_menu[$k] = array(	'name'=>$v['menu'],
											'length'=>mb_strlen($v['menu'], cmfCharset),
											'url'=>addslashes(cmfGetAdminUrl($k)));
						if($main==$k) {
                            $_menu[$k]['sel'] = 1;
                            $selectId = $menuId;
      					}
						$_subMenu[$menuId] = array();
						if(!empty($v['submenu'])) {
							$isSub = true;
							$_subMenu[$menuId][$k] = array(	'name'=>$v['submenu'],
															'url'=>addslashes(cmfGetAdminUrl($k) .
																		cmfAdminControllerUrl::requestUri(array('parentId'=>self::getSubMenuId(), 'id'=>null))));
							if($main==$k) {
								$_subMenu[$menuId][$k]['sel'] = true;
								$header = 'редактировать';
	                            if($_menu[$menuId]['length']<mb_strlen($header, cmfCharset)) {
									$_menu[$menuId]['length'] = mb_strlen($header, cmfCharset);
								}
	                            $_menu[$menuId]['sel'] = 2;
	                            $_menu[$menuId]['name'] .= " <br><small>{$header}</small>";
							}
						}
					} else {
						if($main==$k) {
                            $selectId = $menuId;
                            $header = 'редактировать';
                            $isSub = true;
                            if($_menu[$menuId]['length']<mb_strlen($header, cmfCharset)) {
								$_menu[$menuId]['length'] = mb_strlen($header, cmfCharset);
							}
                            $_menu[$menuId]['sel'] = 2;
                            $_menu[$menuId]['name'] .= " <br><small>{$header}</small>";
						}
						if($v['submenu']) {
							if($v['select'] and !self::isUserMenu($v['select'])) continue;
							$_subMenu[$menuId][$k] = array(	'name'=>$v['submenu'],
															'url'=>addslashes(cmfGetAdminUrl($k) .
																		cmfAdminControllerUrl::requestUri(array('parentId'=>self::getSubMenuId(), 'id'=>null, 'page'=>null))));
							if($main==$k or ($v['select'] and  self::isSelectUserMenu($k))) {
								$_subMenu[$menuId][$k]['sel'] = true;
							}
						}
					}
				}
				$_subMenu = ($isSub and isset($_subMenu[$selectId])) ? $_subMenu[$selectId] : array();


				while(1) {
					array_unshift($_path, $parent);
					$parent = $sql->placeholder("SELECT parent FROM ?t WHERE id=?", db_pages_admin, $parent)
									->fetchRow(0);
					if(!$parent) {
						break;
					}
				}
			}
		}

		list($parent1, $parent2, $parent3) = $_path;
		$_menu = array($parent1, $parent2, $parent3, $_menu, $_subMenu);

		cmfCacheAdmin::setMenu('subMenu'. self::subMenuCache(), $_menu);
		return $_menu;
	}

}

?>