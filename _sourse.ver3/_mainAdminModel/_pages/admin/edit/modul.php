<?php


class _pages_admin_edit_modul extends driver_modul_edit_tree {

	protected function init() {
		parent::init();

		$this->setDb('_pages_admin_edit_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();

		$form->add('parent',	new cmfFormSelectInt());
		$form->add('type',		new cmfFormSelect());

		$form->add('name',		new cmfFormText());
		$form->add('visible',	new cmfFormCheckbox());
		$form->add('isView',		new cmfFormCheckbox());

		if($id = $this->getId()) {
			list(, $type) = $this->getDb()->getType($id);
			cmfGlobal::set('pageType', $type);
		}
	}

	public function loadForm() {
		parent::loadForm();
		$parent = $this->getFilter('parent');
		if($this->getId()) {
			$data = $this->getData();
			$parent = $data['parent'];
			$type = get($data, 'type');
			$modulId = get($data, 'modul');

		} else {
			if($parent) {
				$type = $this->getDb()->getType($parent);
			} else {
				$type = 'tree';
				$smallName = '';
			}
			$modul = false;
		}
		cmfGlobal::set('pageType', $type);


		$form = $this->getForm();



		if($type==='list') {
			$form->addElement('type', 'list', 			'Список страниц');
			$form->addElement('type', 'tree', 			'Узел');

			$form->add('modul',		new cmfFormSelect());
			foreach(cmfAccessModul::getListModulMenu() as $id=>$modul) {
				if(isset($modul['isPack'])) {
					$name = $modul['name'];
				} else {
					$name = '| ----- | '. $modul['name'];
					if(!$modulId) $modulId = $id;
				}

				$form->addElement('modul', $id, $name);
				if(isset($modul['isPack'])) {
					$form->addOptions('modul', $id, 'disabled', 'disabled');
					$form->addOptions('modul', $id, 'style', 'background-color: #000000; color: #FFFFFF; font-weight: bold');
				}
			}

			$form->add('modulMenu',	new cmfFormSelect());
			list($list, $modulName) = cmfAccessModul::getListMenu($modulId);
			$form->addElement('modulMenu', '', 'Не выбрано');
			foreach($list as $id=>$name) {
				$form->addElement('modulMenu', $id, $name);
			}

		} else {

			$form->addElement('type', 'tree', 			'Узел');
			$form->addElement('type', 'list', 			'Список страниц');
		}
	}

	public function onchangeModul() {
		$form = $this->getForm();
		$form->add('modul',		new cmfFormSelect());
		$modulId = $form->handlerElement('modul');

		list($list, $modulName) = cmfAccessModul::getListMenu($modulId);
		$form->select('name', $modulName);
		$js = $form->get('name')->jsUpdate();

		$form->add('modulMenu',	new cmfFormSelect());
		foreach($list as $id=>$name) {
			$form->addElement('modulMenu', $id, $name);
		}
		$js .= $form->get('modulMenu')->jsUpdate();
		$this->getResponse()->addScript($js);
	}



	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(isset($send['type'])) {
			$this->setNewView();
		}
	}

}

?>