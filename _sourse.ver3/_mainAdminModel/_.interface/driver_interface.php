<?php

// обстрактный интерфейс для модулей админки
abstract class driver_interface {


	// родительский элемент в связке tree=>list=>edit
	protected function returnParent() {
		return false;
	}


	// --------------- установка корректных внутренних переменных фильтров ---------------
	// данные Request ---------------
	protected function initRequest() {
	}
	public function getRequest() {
		return cmfRegister::getRequest();
	}

	// получение объекта ajaxResponse
	public function getResponse() {
		return cmfAjax::get();
	}



	static protected function filterId($id) {
		return str_replace(array('"', "'", '<', '>'), '', $id);
	}

	// установка & получение id
	private $idName = false;
	protected function isChageIdName() {
		return (bool)$this->idName;
	}
	protected function setIdName($name) {
		$this->idName = $name;
	}
	protected function getIdName() {
		return $this->idName ? $this->idName : 'id';
	}
	public function setId($id) {
		$this->setFilter($this->getIdName(), $id);
	}
	public function getId() {
		return $this->getFilter($this->getIdName());
	}



	// установка & получение фильтра
	public function setFilter($n, $v) {
		$this->getRequest()->setGet($n, $v);
	}
	public function getFilter($n) {
		return urldecode($this->getRequest()->getGet($n));
	}
	// --------------- /установка корректных внутренних переменных фильтров ---------------



/*	public function setNewView() {
		cmfCommand::set('$isNewView');
		cmfCommand::del('$isReload');
	}*/

	// --------------- команды ---------------
	// проверка команд
	protected function isReload() {
		return cmfCommand::is('$isReload');
	}
	protected function isNewView() {
		return cmfCommand::is('$isNewView');
	}
	protected function isNewRecord() {
		return cmfCommand::is('isNewRecord');
	}
	public function notId() {
		return cmfCommand::get('$notFount');
	}

	// задание команд
	public function setReload() {
		cmfCommand::set('$isReload');
	}
	public function setNewRecord() {
		cmfCommand::set('isNewRecord');
	}
	public function setNewView() {
		cmfCommand::set('$isNewView');
	}
	public function setNotNewView() {
		cmfCommand::del('$isNewView');
	}
	public function setNotFount() {
		cmfCommand::set('$notFount');
	}
	// --------------- /команды ---------------


	// --------------- получение объекта базы ---------------
	protected function getSql() {
		return cmfRegister::getSql();
	}

}

?>
