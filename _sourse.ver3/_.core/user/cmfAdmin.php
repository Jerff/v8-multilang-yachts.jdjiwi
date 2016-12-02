<?php

cmfLoad('user/cmfUserDriver');
class cmfAdmin extends cmfUserDriver {

	public function __construct(){
		$this->setName('session_admin1');

		parent::__construct();
	}

	public function getFields() {
		return array('id', 'login', 'name', 'admin', 'debugError', 'debugSql', 'debugExplain', 'debugCache');
	}

	protected function loadData(&$row) {
		$row = array_merge($row, $this->getDataParam());
	}

	protected function getWhere() {
		return array("(LENGTH(`admin`)>1)");
	}


	protected function setData($data) {
		$group = cmfString::pathToArray($data['admin']);
		$data['group'] = $group;
		$data['groupString'] = implode(',', $group);

		parent::setData($data);
	}


	public function getGroup() {
		return $this->get('group');
	}
	public function getGroupString() {
		return $this->get('groupString');
	}

}

?>