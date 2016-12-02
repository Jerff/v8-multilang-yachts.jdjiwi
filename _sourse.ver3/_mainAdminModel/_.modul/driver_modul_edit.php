<?php


abstract class driver_modul_edit extends driver_interface {

	private $db = null;

	private $name = null;
	private $form = null;
	private $data = null;

	private $isPos = false;

	function __construct() {

		$this->initRequest();

		$this->setName(get_class($this));

		// форма
		$form = new cmfForm('', $this->getName());
		$form->setRequest();
		$this->setForm($form);

		//$this->init();
	}


	public function initModul() {
		$this->init();
	}
	protected function init() {
	}



	// освобождение от ненужных данных
	function free() {
	}



	// имя объекта
	protected function setName($n) {
		$this->name = $n;
	}
	public function getName() {
		return $this->name;
	}


	// установит & вернуть форму объект базы данных
	protected function setDb($db) {
		$this->db = cmfModulLoad($db);
		if($this->isChageIdName()) {
			$this->db->setIdName($this->getIdName());
		}
	}
	public function &getDb() {
		return $this->db;
	}



	// установит & вернуть форму объекта
	protected function setForm(&$form) {
		$this->form = $form;
	}
	public function &getForm() {
		return $this->form;
	}
	public function delForm() {
		unset($this->form);
	}



	// установка и получение данных базы ---------------
	protected function setData(&$data) {
		$this->data = $data;
	}
	public function &getData() {
		return $this->data;
	}
	public function getDataId($id) {
		return get($this->data, $id);
	}



	public function &current() {
		$current = array();
		$current[] = $this->getForm();
		$current[] = new cmfData($this->getData());
		return $current;
	}



	// заполнение объекта данными из базы
	// выбираем данные
	// загружаем форму всему свойствами
	// заполняем форму данными из базы
	public function run() {
		if($id = $this->getId()) $this->runData();
		$this->loadForm();
		if($id) {
			$data = $this->getData();
			if($data) $this->selectForm($data);
		}
	}

	// выборка данных записи из базы
	protected function runData() {
		$data = $this->getDb()->runData();
		$this->setData($data);
		return $data;
	}


	// заполнение формы данными из базы
	// форма должна до этого быть полностью заполненая параметрами
	// функция юзается iframe
	public function runForm() {
		if(!$this->getId()) return;
		$this->runData();
		$data = $this->getData();
		$this->selectForm($data);
	}


	// заполняем форму данными
	public function selectFormInit() {
		$this->getForm()->reset();
	}
	protected function selectForm($data) {
		$this->selectFormInit();
		$this->getForm()->selectAll($data);
	}

	// загружаем форму всему свойствами
	public function loadForm() {
		$this->getForm()->reset();
	}



	public function updateIsError(&$isError) {
		$this->loadForm();
		$data = $this->getForm()->handler();
		$this->setData($data);
		$isError |= $this->getForm()->isError();
		$this->updateIsErrorData($data, $isError);
		$this->loadForm();
	}

	protected function updateIsErrorData($data, &$isError) {
	}

	protected function updateIsErrorDataUri($page, $uri, $name, $length, $where) {
		if(!$uri) {
            if(is_array($name)) {
				$modul = cmfModulLoad($name[0]);
				$modul->initModul();
				$name = $name[1];
            } else {
                $modul = $this;
            }
            $name = $modul->getForm()->handlerElement($name);
			$uri = cmfReformUri($name, $length);
		}
//        pre(555, cmfContentUrl::isUrlExists($page, $this->getId(), $uri));
//        exit;
		if(cmfContentUrl::isUrlExists($page, $this->getId(), $uri)
			or $this->getDb()->isUrlExistsWhere($uri, $where)) {
//			pre(555, cmfContentUrl::isUrlExists($page, $this->getId(), $uri));
//            exit;
            $this->getForm()->setError('uri', 'Адрес "/'. $uri .'/" уже занят!');
			return true;
		}
		return false;
	}


	protected function processingForm($id) {
	    return $this->getForm()->processing($id);
	}

	public function updateOk() {
		if(!cmfGlobal::is('updateOk')) {
			$id = (bool)$this->getId();
			cmfGlobal::set('updateOk', $id);
		} else {
			$id = cmfGlobal::get('updateOk');
		}
		$send = $this->processingForm($id);
		if(count($send)) {
			if($id) {
				$data = $this->getDb()->runData();
				$this->selectForm($data);
				foreach($send as $k=>$v) {
					$this->getForm()->deleteFile($k, $row);
				}
			}

			$this->selectForm($send);
            $this->save($send);
            return true;
		}
		return false;
	}

	public function saveLineOk($name) {
		$this->setNewView();
		$this->getForm()->changeName($name);
		$this->updateOk();
	}

	public function updateError() {
		$this->loadForm();
		$send = $this->getForm()->handler();
		$isError = false;
		$this->updateIsErrorData($send, $isError);
	}

	public function getUpdateError() {
		return $this->getForm()->getError();
	}

	public function getJsSetData($update=true) {
		return $this->getForm()->jsUpdate($update);
	}

	public function getListFile() {
		return $this->getForm()->getListFile();
	}



	// запись данных формы с базу
	public function save($send) {
		$this->saveStart($send);
		if(count($send)) {
			$this->getDb()->save($send);
			$this->saveEnd($send);
			return true;
		}
	}


	public function setNewPos($reload=true) {
		$this->isPos = $reload;
	}
	public function getNewPos() {
		return $this->isPos;
	}


	protected function saveStart(&$send) {
		if(empty($send['pos']))
		if($this->getNewPos() and !$this->getId()) {
			$send['pos'] = $this->getDb()->startSavePos($send);

		} elseif(isset($send['pos']) and empty($send['pos'])
			and ($this->getForm()->isObject('pos') or $this->getNewPos())) {
				$send['pos'] = $this->getDb()->startSavePos($send);
			}
        if(!$this->getId()) {
            $this->setId(0);
        }
	}
	protected function saveStartUri(&$send, $name, $len, $where=null) {
		if(empty($send['uri'])) {
			$uri = $this->getForm()->handlerElement('uri');
			if(!$uri) {
				if(is_array($name)) {
    				$modul = cmfModulLoad($name[0]);
    				$modul->initModul();
    				$name = $name[1];
				} else {
				    $modul = $this;
				}
				$name = $modul->getForm()->handlerElement($name);
				if($name) $send['uri'] = cmfReformUri($name, $len);
			}
		}
		if(isset($send['uri'])) {
            if($where) {
				$send['uri'] = $this->getDb()->updateIsUrlExistsWhere($send['uri'], $where);
            } else {
            	$send['uri'] = $this->getDb()->updateIsUrlExists($send['uri']);
            }
		} elseif(!empty($where)) {
            foreach ($where as $key => $value) {
                if(isset($send[$key])) {
                    $uri = $this->getForm()->handlerElement('uri');
                    $send['uri'] = $this->getDb()->updateIsUrlExists($uri, $where);
                    break;
                }
            }
        }
	}


	protected function saveEnd($send) {
	}



	public function deleteFile($element, $key=-1) {
		$this->run();
		$send = array();
		$this->getForm()->deleteFile($element, $send, $key);
		$this->save($send);
	}

	// удаление записи
	public function delete($list) {
		if(is_null($list)) $list = $this->getId();
		$list = (array)$list;
		if(!count($list)) return;

		$form = $this->getForm();
		$this->getDb()->delete($form, $list);
		return $list;
	}


	public function getListId($where) {
		return $this->getDb()->getListId($where);
	}


	public function getNewLineData() {
		return $this->getForm()->handler();
	}

}

?>
