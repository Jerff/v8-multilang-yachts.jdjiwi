<?php


abstract class driver_modul_edit_param extends driver_modul_edit {


	public function loadForm() {
		$param = $this->getDb()->paramList();

		$form = $this->getForm();
		$form->resetElement();
		$this->resetParam();

		foreach($param as $key=>$value) {
			$this->setParam($value, $key);
			$this->initElement($key);
		}
	}

	protected function initElement($key) {
		$value = $this->getParam($key);
		$type = get($value, 'type');
		$values = get($value, 'value');

		$form = $this->getForm();
		switch($type) {
			case 'radio':
				$value['key'] = $values ? array_keys($values) : array();
			case 'select':
				if($type==='radio')	{
					$form->add($key, new cmfFormRadioInt());
				} elseif($type==='select') {
					$form->add($key, new cmfFormSelectInt());
				} else {
					$form->add($key, new cmfFormSelectInt());
				}


				$form->addElement($key, -1, 'не выбрано');
				$form->select($key, -1);

				if(is_array($values))
				foreach($values as $key2=>$value2)
					$form->addElement($key, $key2, $value2);
				break;

			case 'checkbox':
				$value['key'] = array();
				if(is_array($values))
				foreach($values as $key2=>$value2) {
					$key3 = $key. '_' . $key2;
					$value['key'][$key2] = $key3;
					$form->add($key3, new cmfFormCheckbox(array('label'=>$value2)));
					$form->select($key3, 'no');
				}
				$this->setParam($value, $key);
				break;

			case 'basket':
				$value['key'] = $value['price'] = array();
				if(is_array($values))
				foreach($values as $key2=>$value2) {
					$value['key'][$key2] = $key3 = $key. '_key_' . $key2;
					$form->add($key3, new cmfFormCheckbox(array('label'=>$value2, 'errrorHide')));
					$form->select($key3, 'no');

					$value['price'][$key2] = $key3 = $key. '_price_' . $key2;
					$form->add($key3, new cmfFormTextInt(array('errrorHide')));
				}
				$this->setParam($value, $key);
				break;

			case 'separator': case null:
				$form->add($key, new cmfFormCheckbox());
				$form->select($key, 'no');
				break;

			case 'textarea':
				$form->add($key, new cmfFormTextarea(array('max'=>50000)));
				break;

			default:
				$form->add($key, new cmfFormText(array('max'=>255)));
		}
	}



	private $param = array();
	protected function resetParam() {
		$this->param = array();
	}
	protected function setParam($param, $key) {
		$this->param[$key] = $param;
	}
	public function &getParam($key=null) {
		if(is_null($key)) return $this->param;
		else return $this->param[$key];
	}
	protected function getParamType($key) {
		return get2($this->param, $key, 'type');
	}


	// запуск данных
	public function run() {
		static $run = false;
		if($run) return;
		$run = true;

		$this->loadForm();
		if(!$id=$this->getId()) return;

		$_param = $this->getParam();
        $data = $this->getDb()->runData();
		if(!empty($data['param'])) {
    		$data = unserialize($data['param']);
    		foreach($_param as $param=>$value) if(isset($data[$param])) {
    			$this->runDataElement($id, $param, $value['type'], $data[$param]);
            }
		}
	}

	// запуск парамеретра
	protected function runDataElement($id, $param, $type, $value) {
		$form = $this->getForm();
		switch($type) {
			case 'text':
			case 'textarea':
			case 'select':
			case 'radio':
			case 'separator':
				$form->select($param, $value);
				return;

			case 'checkbox':
				foreach($value as $row) {
					$key3 = $param .'_'. $row;
					$form->select($key3, 'yes');
				}
				return;
		}
	}


	//сохранение данных
	public function save($send) {
		foreach($send as $param=>$value)
			$this->saveElement($param, $value);
	}

	//сохранение элемента
	protected function saveElement($param, $value) {
		$this->getDb()->saveElement($param, $value);
	}

	//удаление элемента
	protected function deleteElement($param, $type, $value=null) {
		$this->setUpdate();
		$this->getDb()->deleteElement($param, $type, $value);
	}


	// добавление элемента
	public function paramAdd($param, $new) {
		$newId = cmfModulLoad('param_edit_db')->paramAdd($param, $new);
		if($newId!==false) {
			$this->getDb()->saveElement($param, $newId);
			$this->run();
			$paramValue = $this->getParam($param);
			$this->newView($param, $paramValue);
		}
	}

	// удаление элемента
	public function paramDel($param, $delete) {
		$newId = cmfModulLoad('param_edit_db')->paramDelete($param, $delete);
        if(!is_null($newId)) {
			switch($type = cmfModulLoad('param_edit_db')->getFeildOfId('type', $param)) {
				case 'select':
				case 'radio':
					$this->saveElement($param, -1);
					break;

				case 'text':
				case 'textarea':
					$this->saveElement($param, null);
					break;

				case 'checkbox':
					$this->saveElement($param .'_'. $delete, 'no');
					break;
			}

        }
        $this->run();
	    $this->newView($param, $this->getParam($param));
	}

	private function newView($param, $paramValue) {
		$js = '';
		$form = $this->getForm();
		$response = $this->getResponse();
		switch($paramValue['type']) {
			case 'radio':
				$response->loadHTML("#param{$param}>td:nth(1)>.selectCheckbox", $form->html($param));
				break;

			case 'select':
				$response->addScript($form->get($param)->jsUpdate());
				break;

			case 'checkbox':
				$basket = $paramValue['type']==='basket';
				$html = '';
				foreach($paramValue['key'] as $key2=>$key3){
					if($basket) {
                        $html .= "\n". $form->label($key3, $form->html($paramValue['price'][$key2], 'class="width50"') .'&nbsp;'. $form->checkbox($key3) .'&nbsp;'. $paramValue['value'][$key2]);
        	        } else {
            	        $html .= "\n". $form->html($key3);
        	        }
				}
				$response->loadHTML("#param{$param}>td>.selectCheckbox", $html);

				$html = '<select name="delete'. $param .'" class="width99">
				<option value="-1">Не выбрано</option>';
                foreach($paramValue['key'] as $key2=>$key3) {
					$html .= "\n". '<option value="'. $key2 .'">'. $paramValue['value'][$key2] .'</option>';
				}
				$html .= '</select>';
				$response->loadHTML("#param{$param}>td>div>.checkboxDelete", $html);
				break;
		}
	}

}

?>