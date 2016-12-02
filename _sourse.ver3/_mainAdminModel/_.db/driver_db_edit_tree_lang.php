<?php


abstract class driver_db_edit_tree_lang extends driver_db_edit_tree {

	abstract protected function getTableLang();

    public function loadFormData() {
        if($this->getId()) {
            $res = $this->getSql()->placeholder("SELECT id, parent, level FROM ?t WHERE ?w AND (path IS NULL OR path NOT LIKE '%[?i]%') ORDER BY ?o", $this->getTable(), $this->loadFormFilterParent(), $this->getId(), $this->getSort())
                                    ->fetchAssocAll('parent', 'id');
        } else {
            $res = $this->getSql()->placeholder("SELECT id, parent, level FROM ?t ORDER BY pos", $this->getTable())
                                    ->fetchAssocAll('parent', 'id');
        }
        if($res) {
            $list = array();
            foreach($res as $row) {
                $list = array_merge($list, array_keys($row));
            }

            $lang = $this->getSql()->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", $this->getTableLang(), $list)
                                    ->fetchAssocAll('id', 'lang');
            foreach($res as $parent=>&$list) {
                foreach($list as $id=>&$row) {
                    cmfLang::completeList($row, get($lang, $id), array('name'=>'name'), cmfAdminNotTranslate);
                }
            }
		}
		return $res;
	}

	/* функции для дерева */
	// возвращает массив пути
	public function path(&$data) {
		$sql = $this->getSql();
		if($id=$this->getId()) {
			$path = $data['path'] .'['. $id .']';
			$path = explode('][', substr($path,1,-1));
		} else {
			if($parent = $this->getFilterParent()) {
				list($path) = $sql->placeholder("SELECT path FROM ?t WHERE id=?", $this->getTable(), $parent)
										->fetchRow();
				$path = $path .'['. $parent .']';
				$path = explode('][',substr($path,1,-1));
			} else $path = array();
			$path[] = 0;
		}

		$name = $sql->placeholder("SELECT id, name FROM ?t WHERE id ?@", $this->getTable(), $path)
						->fetchRowAll(0, 1);

		$path_name = array();
		foreach($path as $value)
			$path_name[$value]['name'] = isset($name[$value]) ? $name[$value] : 'Новая запись';

		return $path_name;
	}




}

?>