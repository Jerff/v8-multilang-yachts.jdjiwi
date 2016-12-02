<?php


class param_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_param;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('param');
	}


	static public function paramSort($a, $b) {
		$a = str_replace(array(' ', ','), array('', '.'), $a);
		$b = str_replace(array(' ', ','), array('', '.'), $b);

		preg_match_all('~([0-9.]+|[a-zа-я]+)~i', $a, $tmpA);
		$tmpA = $tmpA[1];

		preg_match_all('~([0-9.]+|[a-zа-я]+)~i', $b, $tmpB);
		$tmpB = $tmpB[1];

		$c = count($tmpA);
		$b = count($tmpB);
		if($c<$b) {
			$c = $b;
		}
		for($i=0; $i<$c; $i++) {
            $a = get($tmpA, $i);
            $b = get($tmpB, $i);
            if(is_null($a)) {
            	return 1;
            }
            if(is_null($b)) {
            	return -1;
            }
            if($a!==$b) {
            	return ($a < $b) ? -1 : 1;
            }
		}
		return 0;
	}


	protected function paramGet($param) {
		$data = $this->getDataId($param);
		if(!empty($data['value'])) {
            return unserialize($data['value']);
		} else {
			return array();
		}
	}
	protected function paramSet($param, $value) {
		$this->saveId($param, array('value'=>serialize($value)));
	}



	public function paramUpdate($param, $id, $value) {
		if(!empty($value)) {
            $data = $this->paramGet($param);
            if(isset($data[$id])) {
	            $data[$id] = $value;
                uasort($data, array('param_edit_db', 'paramSort'));
	            $this->paramSet($param, $data);
	            cmfGlobal::set('$paramLog', 'Параметр обновлен');
	            return $data;
            } else {
            	cmfGlobal::set('$paramLog', 'Сначало необходимо добавить параметр');
            }
		} else {
			cmfGlobal::set('$paramLog', 'Поле не заполнено');
		}
		return false;
	}


	public function paramAdd($param, $value) {
		$value = trim($value);
		if(empty($value)) {
			cmfGlobal::set('$paramLog', 'Поле не заполнено');
			return false;
		}

		$data = $this->paramGet($param);
		if(array_search($value, $data)) {
			cmfGlobal::set('$paramLog', 'Параметр уже существует');
			return false;
		}

		$is = cmfString::translate(str_replace(array(' ', "\t", "\n", '-', '+', '_', '.', ','), '', $value));
		foreach($data as $k=>$v) {
			$v = cmfString::translate(str_replace(array(' ', "\t", "\n", '-', '+', '_', '.', ','), '', $v));
			if($is===$v) {
				cmfGlobal::set('$paramLog', 'Параметр уже существует');
				return false;
			}
		}

		if($data) {
    		array_push($data, $value);
            uasort($data, array('param_edit_db', 'paramSort'));
		} else {
			$data[1] = $value;
		}
		$this->paramSet($param, $data);
		cmfGlobal::set('$paramLog', 'Параметр добавлен');
		return array_search($value, $data);
	}


	public function paramDelete($param, $id) {
		$data = $this->paramGet($param);
		if(isset($data[$id])) {
            unset($data[$id]);
            $this->paramSet($param, $data);
            cmfGlobal::set('$paramLog', 'Параметр удален');

            //$this->getSql()->del(db_param_separator, array('param'=>$param));
            //$this->getSql()->del(db_param_checkbox, array('param'=>$param, 'AND', 'value'=>$id));
            //$this->getSql()->del(db_param_select, array('param'=>$param, 'AND', 'value'=>$id));

            return $data ? key($data) : false;
		} else {
            cmfGlobal::set('$paramLog', 'Параметр не выбран');
            return null;
		}
	}

}

?>