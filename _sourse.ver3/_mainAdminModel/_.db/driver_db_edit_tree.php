<?php


abstract class driver_db_edit_tree extends driver_db_edit {


	protected function getSort() {
		return array('pos');
	}

	protected function startSaveWhere() {
		return array('parent');
	}
	protected function getIsUrlExistsWhere() {
		return array('parent');
	}

	protected function getFilterParent() {
		return $this->getFilter('parent');
	}

	// какое-либо действия над данными перед загрузкой в формы
	// установка значения обьектов форм перед загрузкой данных
	protected function loadFormFilterParent() {
		return array(1);
	}

	public function loadFormData() {
		if($this->getId()) {
			return $this->getSql()->placeholder("SELECT id, parent, level, name FROM ?t WHERE ?w AND (path IS NULL OR path NOT LIKE '%[?i]%') ORDER BY ?o", $this->getTable(), $this->loadFormFilterParent(), $this->getId(), $this->getSort())
									->fetchAssocAll('parent', 'id');
		} else {
			return $this->getSql()->placeholder("SELECT id, parent, level, name FROM ?t ORDER BY pos", $this->getTable())
									->fetchAssocAll('parent', 'id');
		}
	}


	public function getLevelPath($id) {
		return $this->getSql()->placeholder("SELECT level, path FROM ?t WHERE id=?", $this->getTable(), $id)
									->fetchAssoc();
	}

	public function getParentIsUri($id) {
		return $this->getSql()->placeholder("SELECT isUri FROM ?t WHERE id=(SELECT parent FROM ?t WHERE id=?)", $this->getTable(), $this->getTable(), $id)
									->fetchRow(0);
	}

	public function getIsUri($id) {
		return $this->getSql()->placeholder("SELECT isUri FROM ?t WHERE id=?", $this->getTable(), $id)
										->fetchRow(0);
	}

	public function setChildPath($path, $id) {
		$sql = $this->getSql();
		$table = $this->getTable();
		$sql->placeholder("UPDATE ?t SET path = CONCAT(?, SUBSTRING(path, LOCATE('[?i]', path)))  WHERE path LIKE '%[?i]%'", $table, $path, $id, $id);
		$sql->placeholder("UPDATE ?t SET level = CHAR_LENGTH( path ) - CHAR_LENGTH( REPLACE( path , '[' , '' ) )  WHERE path LIKE '%[?i]%'", $table, $id);
	}


	public function getIdTree($listid) {
		if(!count($listid)) return $listid;
		$listid = (array)$listid;

		$str = '';
		foreach($listid as $id)
			$str .= "OR path LIKE '%[". (int)$id ."]%'";

		return $this->getSql()->placeholder("SELECT id FROM ?t WHERE id ?@ $str", $this->getTable(), $listid)
									->fetchRowAll(0, 0);
	}


	// запись данных формы с базу
	protected function saveEnd($id, $send) {
		$oldId = $this->getId();
		parent::saveEnd($id, $send);
        if(isset($send['parent']) or isset($send['name'])) {
			$this->setNewView();
		}
		if(isset($send['visible'])) {
			cmfAdminTree::updateVisible($this->getTable(), $id);
		}
		if(cmfCommand::is('$formIsUri') and (isset($send['uri']) or isset($send['parent']))) {
			list($isUri, $result) = cmfAdminTree::updateUri($this->getTable(), $id);
			if($result) {
			    foreach($result as $k=>$uri) {
				    $this->setId($k);
    				$this->save(array('isUri'=>$uri));
	    		}
	        }
			$this->setId($oldId);
		}
		if(cmfCommand::is('$formIsUrl') and (isset($send['uri']) or isset($send['parent']) or isset($send['url']) or isset($send['isUrl']))) {
			list($isUri, $result) = cmfAdminTree::updateUrl($this->getTable(), $id);

			if($result) {
			    foreach($result as $k=>$uri) {
				    $this->setId($k);
    				$this->save(array('isUri'=>$uri));
	    		}
	        }
			$this->setId($oldId);
		}
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