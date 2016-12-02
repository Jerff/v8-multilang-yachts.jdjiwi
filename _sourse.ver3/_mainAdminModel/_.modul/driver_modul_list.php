<?php


abstract class driver_modul_list extends driver_modul_edit {


	public function getNameID($id) {
		if(!$id) $id = '{0}';
		return $this->getName() . $id;
	}


	function free() {
		parent::free();
		$form = $this->getForm()->free();
	}

	public function loadForm2() {
	}
	public function loadFormNewLine($index) {
	}


	// заполнение формы данными из базы
	public function runForm() {
		if(!$id = $this->getId()) return;
		$this->runData();
		$this->selectForm($this->getDataId($id));
	}


	// вызывается выбора только для одной записи (iframe, new_line)
	public function runData() {
		$id = array($this->getId());
		$this->runList($id);
	}

	// выборка данных записи из базы
	public function runList($id=null, $offset=null, $limit=null) {
		$this->loadForm();

		$data = $this->getDb()->runList($id, $offset, $limit);
		$this->setData($data);

		return array_keys($data);
	}

	public function getTotal() {
		return $this->getDb()->getTotal();
	}


	public function currentForm($id) {
		$this->getForm()->changeName($this->getNameID($id));
	}

	public function &currentList($index, $id) {
		$form = $this->getForm();
		$form->changeName($this->getNameID($index));
		$data = $this->getDataId($id);
		$this->selectForm($data);

		$current = array();
		$current[] = $form;
		$current[] = new cmfData($data);

		return $current;
	}


	public function deleteFile($element) {
		$id = $this->getId();
		$data = $this->getDb()->runList($id);
		$form = $this->getForm();
		$form->selectAll($data[$id]);

		$send = array();
		$form->deleteFile($element, $send);
		$this->save($send);
	}


	// обновление форм
	public function updateIsErrorList($listId, &$isError) {
		$this->loadForm();
		$form = $this->getForm();

		foreach($listId as $index=>$id) {
			$form->changeName($this->getNameID($index));
			$data = $form->handler();
			if($form->isError()) {
				$isError = true;
				break;
			}
			$this->updateIsErrorData($data, $isError);
		}
	}

	public function updateListOk($listId=null) {
		$form = $this->getForm();
		$data = $this->getDb()->runList($listId);

		foreach($listId as $index=>$id) {
			$this->setId($id);
			$form->changeName($this->getNameID($index));
			$send = $form->processing();
			if(isset($data[$id])) {
				$this->selectForm($data[$id]);
				foreach($send as $k=>$v)
					$form->deleteFile($k, $data[$id]);
			}

			$this->save($send);
			if(isset($send['pos'])) $this->setNewView();
		}
		$this->runList($listId);
	}

	public function updateErrorList($listId) {
		$isError = false;
		$js = '';
		$form = $this->getForm();
		foreach($listId as $index=>$id) {
			$this->setId($id);
			$form->changeName($this->getNameID($index));
			$data = $form->handler();
			$this->updateIsErrorData($data, $isError);

			$js .= $form->jsUpdate(false);
		}
		return $js;
	}


	public function getJsSetDataList($name, &$modul, $listId=null) {
		$js = '';
		$form = $this->getForm();

		if(is_null($listId)) $listId = (array)$this->getId();
		foreach($listId as $index=>$id) {
            $this->setId($id);
			$form->changeName($this->getNameID($index));
			$this->selectForm($this->getDataId($id));
            $this->loadForm2();

			$file = $this->getListFile();
			foreach($file as $f) {
				$modul->getJsFile($name, $f);
			}
			$js .= $form->jsUpdate();
		}
		return $js;
	}





	public function newLine($index, &$data) {
		$this->getDb()->loadData($data);
		$line = array('data'=>$data);

		$form = $this->getForm();
		$form->changeName($this->getNameID($index));
		$this->selectForm($data);
		return $form;
	}

}

?>