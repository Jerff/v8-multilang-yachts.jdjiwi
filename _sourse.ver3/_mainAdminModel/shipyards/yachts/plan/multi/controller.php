<?php


class shipyards_yachts_plan_multi_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'shipyards_yachts_plan_multi_modul');

		// url
		$this->setSubmitUrl('/admin/shipyards/yachts/plan/');

		$this->callFuncWriteAdd('newLine');
	}

	protected function updateSortKey(&$list, &$new) {
        if($new) krsort($new);
    }

	public function rewind() {
		parent::rewind();
		$id = array('{0}'=>0,
		            'm'. time()=>0,
		            'm'. (time()+1000)=>0);
		$this->setDataId($id);
	}

	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

}

?>