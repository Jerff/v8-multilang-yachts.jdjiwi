<?php


class _lang_list_section_db extends driver_db_edit_product_action {

	function __construct() {
		$this->setIdName('main');
		parent::__construct();
	}

	protected function getTable() {
		return db_lang_section;
	}

	protected function getActionList() {
		$res = array();
		$res['news']['name'] = 'Новости';
		$res['article']['name'] = 'Статьи';
		$res['info']['name'] = 'Информация';
		$res['content']['name'] = 'Контент';

		$res['main']['name'] = 'Главная';
		$res['foto']['name'] = 'Фотографии';
		$res['wallpapers']['name'] = 'Обои';

		return $res;
	}

	protected function action() {
		return 'section';
	}

	public function save($send) {
		if($send) {
			$action = $this->action();
			foreach($this->getActionKey() as $key) {
				if(!isset($send[$key])) continue;
				$value = get($send, $key);
				if($value==='no') {
					$this->getSql()->del($this->getTable(), array($action=>$key));
				} elseif($value==='yes') {
					$this->getSql()->replace($this->getTable(), array($action=>$key));
				}
			}
		}
	}

	protected function getWhere() {
		return array(1);
	}

	public function updateData($list, $send) {
	}

}

?>