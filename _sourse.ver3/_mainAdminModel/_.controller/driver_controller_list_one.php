<?php


class driver_controller_list_one extends driver_controller_list {


	function __construct($limitAll=null, $limitPage=null) {
		$this->setIdName('list');
		parent::__construct($limitAll, $limitPage);

		$this->callFuncWriteAdd('copy');
	}


	protected function getLimit() {
		return 1000;
	}


	public function getAddChildUrl($opt=null){
		$opt['add'] = 1;
		return $this->getNewUrl($opt);
	}


	public function getCount() {
		$listId = $this->getDataId();
		return $this->getModul()->getCount($listId);
	}

	public function getEditUrl($opt=null) {
		$opt['id'] = $this->getId();
		return parent::getEditUrl($opt);
	}

}

?>