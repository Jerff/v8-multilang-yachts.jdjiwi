<?php


class cmfUpdatePages {

	public static function start(){
		cmfBackupTable::exportDumpPages();
		self::updateMain();
		self::updateAdmin();
		cmfUpdateCompile::start();
		cmfCacheAdmin::deleteTag('menu,access');
	}


	// ��������� ������� ��� �������
	public static function updateAdmin() {
		ignore_user_abort(true);

		$sql = cmfRegister::getSql();
		$read = $sql->placeholder("SELECT modul FROM ?t", db_access_read)
						->fetchRowAll(0, 0);
		$templateId = 0;
		$_template = array('admin.index.php'=>$templateId);
		$_admin = array();
		$i = 1;
		foreach(cmfAccessModul::getListPageOfModul($read) as $id=>$row) {
            $page = array();
            if(!$row['system']) {
	            $template = $row['template'];
	            if($template) {
	            	$template .= '.php';
	            	if(isset($_template[$template])) {
						$page['t'] = $_template[$template];
	            	}  else {
	            		$_template[$template] = $i;
	            		$page['t'] = $_template[$template];
						$i++;
					}
	            } else {
	            	$page['t'] = $templateId;
	            }
				$page['url'] = "'". ($row['url'] ? $row['url'] : $id) ."'";
	            if(!empty($row['preg'])) {
	                $page['preg'] = "array('#^". preg_replace("#([ |\n]+)#", "$#','#^", $row['preg']) ."$#')";
	            }
			}
            $page['path'] = "'{$row['path']}'";
            $_admin[$id] = $page;
		}

		$res = $sql->placeholder("SELECT id,parent,name FROM ?t WHERE type IN('tree', 'list') AND visible='yes' ORDER BY pos", db_pages_main);
		$_tree_main = array();
		$id = array();
		while($row = $res->fetchAssoc()) {
			$id[] = $row['id'];
			$_tree_main[$row['parent']][$row['id']]['name'] = $row['name'];
		}
		$res->free();

		$res = $sql->placeholder("SELECT * FROM ?t WHERE parent ?@ AND type='pages' AND path!='' AND visible='yes' ORDER BY pos", db_pages_main, $id);
		$_main = array();
		while($row = $res->fetchAssoc()) {
			$data = array();
			$data['small_name'] = $row['small_name'];
			//$data['name']=$row['name'];

			$data['part'] = "'{$row['part']}'";
			if(!empty($row['url'])) $data['url']="'{$row['url']}'";
			$_main[$row['parent']][$row['id']] = $data;
		}
		$res->free();

		ignore_user_abort(true);
		$str = self::createUrlTreeAdmin($_admin);
		$str .= "\n". self::createUrlTreeAdminMain($_tree_main, $_main);

		$str .="\n\$t = array();";
		foreach($_template as $k=>$v) {
            $str .="\n\$t[$v] = '$k';";
		}

		$str = "<?php
$str
cmfPages::select(\$p, \$t);
?>";
		file_put_contents('_.config/pageAdmin.php', $str);
		ignore_user_abort(false);
	}

	public static function createUrlTreeAdmin(&$_admin) {
		$str = "\n\$p = array();";
		foreach($_admin as $key=>$value) {
			$str .= "\n\$p['{$key}']=array(";
			$sep = '';
			foreach($value as $key2=>$value2) {
				$str .= $sep ."\n'$key2'=>$value2";
				$sep = ',';
			}
			$str .= "\n);\n";
		}
		return $str;
	}


	public static function createUrlTreeAdminMain(&$_tree, &$_pages, $parent=0) {
		$str = '';
		foreach($_tree[$parent] as $key=>$value) {
			$str .= "\n// --------- {$value['name']} ---------";
			if(isset($_pages[$key])) {
				foreach($_pages[$key] as $key2=>$value2) {
					$str .= "\n\$p['{$value2['small_name']}']=array(";
					unset($value2['small_name']);
					$sep = '';
					foreach($value2 as $key3=>$value3) {
						$str .= $sep ."\n'$key3'=>$value3";
						$sep = ',';
					}

					$str .= "\n);\n";
				}
			}
			if(isset($_tree[$key])) {
				$str .= self::createUrlTreeAdminMain($_tree, $_pages, $key);
			}
			$str .= "// --------- /{$value['name']} ---------\n";
		}
		return $str;
	}




	// ��������� ������� ��� �����
	public static function updateMain() {
		ignore_user_abort(true);
		$sql = cmfRegister::getSql();

		$res = $sql->placeholder("SELECT id, parent, name FROM ?t WHERE type IN('tree', 'list') AND visible='yes' ORDER BY pos", db_pages_main);
		$tree_main = $id = array();
		while($row = $res->fetchAssoc()) {
			$id[] = $row['id'];
			$tree_main[$row['parent']][$row['id']]['name'] = $row['name'];
		}
		$res->free();

		$res = $sql->placeholder("SELECT * FROM ?t WHERE parent ?@ AND type IN('pages', 'pagesSystem') AND path!='' AND visible='yes' ORDER BY pos", db_pages_main, $id);
		$main = array();
		$_templates = array();
		$i =0;
		while($row = $res->fetchAssoc()) {
			$data = array();
			$data['small_name'] = $row['small_name'];

			$templates = $row['templates'];
			if($templates) {
				if(!isset($_templates[$templates])) {
					$_templates[$templates] = $i;
					$data['t'] = $_templates[$templates];
					$i++;
				} else {
					$data['t'] = $_templates[$templates];
				}
			}

			$data['part'] = "'{$row['part']}'";
			if(!empty($row['url'])) $data['url']="'{$row['url']}'";
			if(!empty($row['pattern'])) $data['pattern'] = "array('#^".preg_replace("#([ \n]+)#","$#','#^", $row['pattern']) ."$#')";
			$data['path']="'{$row['php_path']}'";

			if($row['cacheBrousers']==='no')	$data['brousers']='false';
			if($row['cache']==='no')			$data['!cache']='true';
			if($row['cacheMain']==='yes')		$data['isMain']='true';
			if($row['cacheUrl']==='no')			$data['noUrl']='true';
			if($row['cacheRequestUri']==='yes')	$data['Request']='true';

			$main[$row['parent']][$row['id']] = $data;
		}
		$res->free();



		// ��������� ��������� ������
		ignore_user_abort(true);

		list($main, $url, $preg) = self::createUrlTreeMain($tree_main, $main);
        $url .= $preg;
		$url .="\n\$t = array();";
		foreach($_templates as $k=>$v) {
            $url .="\n\$t[$v] = '$k';";
		}

		$lang = cmfLang::getPregUrl();
		$str = "<?php
$main
$url
cmfPages::select('$lang', \$p, \$n, \$pr, \$t);

?>";
		file_put_contents('_.config/pageMain.php', $str);
		ignore_user_abort(false);
	}

	public static function createUrlTreeMain(&$_tree, &$_pages, $parent=0) {
		if($parent) {
			$str = "\n";
			$pages = "\n";
			$preg = "\n";

		} else {
			$str = "\n\$p = array();";
			$pages = "\$n = array();\n";
			$preg = "\$pr = array();\n";

		}

		foreach($_tree[$parent] as $key=>$value) {
			$str .= "\n// --------- {$value['name']} ---------";
			if(isset($_pages[$key])) {
				foreach($_pages[$key] as $key2=>$value2) {
					if(isset($value2['url'])) {
						if(!preg_match('/(\([0-9]+\))/', $value2['url'])) {
							$pages .= "\$n[{$value2['part']}][{$value2['url']}]='{$value2['small_name']}';\n";

						}
					}

					if(isset($value2['pattern'])) {
						$preg .= "\$pr[{$value2['part']}]['{$value2['small_name']}']={$value2['pattern']};\n";
						unset($value2['pattern']);
					}

					$str .= "\n\$p['{$value2['small_name']}']=array(";
					unset($value2['small_name']);
					$sep = '';
					foreach($value2 as $key3=>$value3) {
						$str .= $sep ."\n'$key3'=>$value3";
						$sep = ',';
					}

					$str .= "\n);\n";
				}
			}
			if(isset($_tree[$key])) {
				list($str2, $pages2, $preg2) = self::createUrlTreeMain($_tree, $_pages, $key);
				$str .= $str2;
				$pages .= $pages2;
				$preg .= $preg2;
			}
			$str .= "// --------- /{$value['name']} ---------\n";
		}
		return array($str, $pages, $preg);
	}

}

?>
