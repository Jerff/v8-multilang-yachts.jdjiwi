<?php


abstract class driver_db_edit_param extends driver_db_edit {

	abstract public function paramList();
	abstract protected function getGroup();


    public function deleteYachts($id) {
		$this->getSql()->del(db_param_select, array('group'=>$this->getGroup(), 'AND', 'id'=>$id));
		$this->getSql()->del(db_param_checkbox, array('group'=>$this->getGroup(), 'AND', 'id'=>$id));
	}

    public function save($send) {
		return parent::save(array('param'=>serialize($send)));
	}

	public function saveElement($param, $value) {
		static $_param = null;
		static $data = null;
		if(!$_param) {
		    $_param = $this->paramList();

		    $data = $this->getSql()->placeholder("SELECT param FROM ?t WHERE id=?", $this->getTable(), $this->getId())
		                            ->fetchRow(0);
            $data = cmfString::unserialize($data);
		}
		list($id) = explode('_', $param);
		switch($type=get2($_param, $id, 'type')) {
			case 'select':
			case 'radio':
				if($value<0 or is_null($value)) {
					unset($data[$param]);
					$this->getSql()->del(db_param_select, array('group'=>$this->getGroup(), 'AND', 'id'=>$this->getId(), 'AND', 'param'=>$param));
				} else {
					$data[$param] = $value;
					$this->getSql()->replace(db_param_select, array('group'=>$this->getGroup(), 'id'=>$this->getId(), 'param'=>$param, 'value'=>$value));
				}
				break;

			case 'text':
			case 'textarea':
				if(empty($value) or is_null($value)) {
                    unset($data[$param]);
				} else {
					$data[$param] = $value;
				}
				break;

			case 'checkbox':
				if($value==='yes' or $value==='no') {
					$param = explode('_', $param);
					if(count($param)!=2) break;
					list($param, $key) = $param;
				} else {
					$key = $value;
					$value = 'yes';
				}
				if($value==='yes') {
					$data[$param][$key] = $key;
					$this->getSql()->replace(db_param_select, array('group'=>$this->getGroup(), 'id'=>$this->getId(), 'param'=>$param, 'value'=>$key));
				} else {
					unset($data[$param][$key]);
					$this->getSql()->del(db_param_select, array('group'=>$this->getGroup(), 'AND', 'id'=>$this->getId(), 'AND', 'param'=>$param, 'AND', 'value'=>$key));
				}
				break;

			case 'separator':
				if($value==='yes') {
					$data[$param] = true;
				} else {
					unset($data[$param]);
				}
				break;

			default:
			    return;
		}
		$this->save($data);
		$this->updateData($param);
	}

}

?>