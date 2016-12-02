<?php


abstract class driver_controller_list_tree extends driver_controller_list {


	protected function init() {
		parent::init();
		$this->setTotal(false);

		$this->callFuncWriteAdd('copy');
	}

	protected function getLimit() {
		return 1000;
	}


	public function getNewUrl($opt=null) {
		$opt['id'] = null;
		//$opt['parent'] = null;
		return $this->getEditUrl($opt);
	}


	public function getAddChildUrl($opt=null){
		$opt['parent'] = $this->getKey();
		$opt['id'] = null;
		return $this->getEditUrl($opt);
	}




	// заполнение форм данными из базы
	public function run($id=null) {

		$id = $this->getModul()->runList($id);
		$this->setDataId($id);

		$modulAll = $this->getModulAll();
		while(list($name, $modul) = each($modulAll)) {
			if($name!=='main') {
				$modul->runList($id);
			}
		}
	}


	public function getColspanLine($data) {
		return "colspan=". ($this->getKey() ? $data->colspan : $this->getColspan()) .'"';
	}
	public function getColspan() {
		return $this->getModul()->getColspan();
	}



	protected function move1($id, $type=null) {
		if($this->getModul()->getDb()->move1($id)) {
			$this->setNewView();
		}
	}

	protected function move2($id, $type=null) {
		if($this->getModul()->getDb()->move2($id)) {
			$this->setNewView();
		}
	}

}

?>