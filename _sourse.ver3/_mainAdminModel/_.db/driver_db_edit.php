<?php


abstract class driver_db_edit extends _interface_db_orm {

	function __construct() {
		$this->initRequest();
		$this->init();
	}

	// --------------- инициализация данных ---------------
	protected function init() {
	}



	// --------------- получение таблицы базы ---------------
	abstract protected function getTable();
/*
	protected function getTable() {
		return constant;
	}
*/


	// --------------- WHERE для запросов к данных БД ---------------
	protected function getWhere() {
		return $this->getWhereId($this->getId());
	}
	protected function getWhereId($id) {
		return array('id'=>$id);
	}


	// --------------- SELECT fileds FROM.. ---------------
	protected function getFields() {
		return array('*');
	}



	// --------------- загрузка и преобразование данных ---------------
	// --------------- выборка данных записи из базы по фильтру ---------------
	public function runData() {
		$data = $this->getSql()->placeholder("SELECT ?f FROM ?t WHERE ?w", $this->getFields(), $this->getTable(), $this->getWhere())
									->fetchAssoc();
		if(!$data) {
			$this->setNotFount();
			return null;
		}
		$this->loadData($data);
		return $this->loadSetData($data);
	}

	// предобработка данных из базы
	public function loadData(&$data) {
	}

	// сохранение данных в массив для последующей передачи верхнему уровню
	protected function loadSetData(&$data) {
		return $data;
	}



	// --------------- запись данных в базу ---------------
	// запись данных
	public function save($send) {
		$id = $this->getId();
		$id = $this->saveId($id, $send);
		return $this->saveSetId($id);
	}

	// запись данных по $id
	public function saveId($id, $send) {
		$new = $this->getSql()->add($this->getTable(), $send, $this->getWhereId($id));
		if(!$id) $id = $new;
		$this->saveEnd($id, $send);
		return $id;
	}

	protected function saveSetId($id) {
		if($this->getId()!=$id) {
			$this->setNewRecord();
		}
		$this->setId($id);
	}

	// --------------- корректировка нумерации в базе ---------------
	// фильтр для коррекитировки
	protected function startSaveWhere() {
		return array();
	}
	protected function startSavePosField() {
		return 'pos';
	}
	// коррекитировка
	public function startSavePos(&$send) {
		$id = $this->getId();
		$where = array("a.`id`!='$id'");
		$join = $this->startSaveWhereJoin($send, $where);

		$table = $this->getTable();
		if($join) {
			list($pos) = $this->getSql()->placeholder("SELECT max(a.pos) FROM ?t a, ?t b WHERE ?w", $table, $table, $where)
											->fetchRow();
		} else {
			list($pos) = $this->getSql()->placeholder("SELECT max(a.pos) FROM ?t a WHERE ?w", $table, $where)
											->fetchRow();
		}
		return (int)$pos + 1;
	}
	protected function startSaveWhereJoin($send, &$where) {
		$join = false;
		$sql = $this->getSql();
		$fields = $this->startSaveWhere();

		$id = $this->getId();
		if($id) {
			foreach($fields as $name) {
				if(count($where)) $where[] = 'AND';
				if(isset($send[$name])) {
					$where[] = $this->startSaveWhereJoinFieldValue('a', $name, $send[$name]);
	            }
				else {
					$where[] = $this->startSaveWhereJoinFieldTable('a', $name, 'b');
					$join = true;
				}
			}
			if($join) {
				$where[] = 'AND';
				$where[] = "b.`id`='$id'";
			}
		} else {
			foreach($fields as $name) {
				if(isset($send[$name])) {
					if(count($where)) $where[] = 'AND';
					$where[] = $this->startSaveWhereJoinFieldValue('a', $name, $send[$name]);
				}
			}
		}
		return $join;
	}
	protected function startSaveWhereJoinFieldValue($t, $f, $v) {
		$sql = $this->getSql();
		return $t .'.'. $sql->quoteParam($f) .'='. $sql->quoteString($v);
	}
	protected function startSaveWhereJoinFieldTable($t1, $f1, $t2, $f2=null) {
		$sql = $this->getSql();
		return $t1 .'.'. $sql->quoteParam($f1) .'='. $t2 .'.'. $sql->quoteParam(is_null($f2) ? $f1 : $f2);
	}



	// --------------- дейтсвия после запис в базу ---------------
	protected function updateController() {
		return false;
	}

	protected function saveEnd($id, $send) {
		if($this->returnParent()) {
		    cmfModulLoad($this->returnParent())->saveEnd($id, $send);
		}
		$this->setUpdateData($send);
	}

	// --------------- обновление данных, кеша и других связанных с записьями данных ---------------
    private $update = array();
    private $updateList = array();
    public function runUpdateData() {
        if($this->update) {
    		if($this->returnParent()) {
                $parent = cmfModulLoad($this->returnParent());
                $parent->updateData($this->updateList, $this->update);
                if($parent->updateController()) {
                    call_user_func(array($parent->updateController(), 'update'), $this->updateList, $this->update);
        		}
    		} elseif($this->updateController()) {
                call_user_func(array($this->updateController(), 'update'), $this->updateList, $this->update);
    		} else {
                $this->updateData($this->updateList, $this->update);
    		}
        }
    }
	public function setUpdateData($send) {
		if(!is_array($send)) {
			$send = array($send=>$send);
		}
		$this->update = array_merge($this->update, $send);
		$this->updateList[$this->getUpdateId()] = $this->getUpdateId();
	}
	public function getUpdateId() {
		return $this->getId();
	}
	public function updateData($list, $send) {
	}


	// --------------- проверка на присутсвие в базе похожих uri ---------------
	// фильтр проверки
	protected function getIsUrlExistsWhere() {
		return array(1);
	}
	// провека на присутсвтие урла в базе
	public function isUrlExistsWhere($url, $where=array(1)) {
		if($this->getId()) {
			$id = (int)$this->getId();
        	$where[] = "AND id!='{$id}'";
		}
        return $this->getSql()->placeholder("SELECT 1 FROM ?t WHERE ?w AND uri=? ", $this->getTable(), $where, $url)
									->numRows();
	}
 	// провека и корректировка на присутсвтие урла в базе
 	public function updateIsUrlExists($url) {
    	return $this->updateIsUrlExistsWhere($url, $this->getIsUrlExistsWhere());
	}
	// провека и корректировка на присутсвтие урла в базе по условию where
 	public function updateIsUrlExistsWhere($url, $where) {
        if($this->isUrlExistsWhere($url, $where)) {
			preg_match('~(^(.*?\_)([0-9]*)$)~', $url , $tmp);
			if(isset($tmp[3])) {
				$url = $tmp[2] . ($tmp[3]+1);
			} else {
				$url = $url . '_1';
			}
			return $this->updateIsUrlExistsWhere($url, $where);
    	}
    	return $url;
	}



	// --------------- удаление записи ---------------
	public function delete(&$form, $list_id) {
		$table = $this->getTable();
		$sql = $this->getSql();
		$list_id = (array)$list_id;
		foreach($list_id as $id) {
			$this->setId($id);
			$res = $sql->placeholder("SELECT * FROM ?t WHERE ?w", $this->getTable(), $this->getWhere());
			while($row=$res->fetchAssoc()) {
				$form->selectAll($row);
				$form->deleteFileAll();
			}
			$res->free();
		}

		$sql->del($table, $this->getWhereId($list_id));
		$sql->optimize($table);

		foreach($list_id as $id) {
			$this->setId($id);
		}
        $parent = $this->returnParent() ? cmfModulLoad($this->returnParent()) : $this;
		if($parent->updateController()) {
            call_user_func(array($parent->updateController(), 'delete'), $this->getDeleteModelId($list_id));
		}
	}
	protected function getDeleteModelId($list_id) {
		return $list_id;
    }

}

?>