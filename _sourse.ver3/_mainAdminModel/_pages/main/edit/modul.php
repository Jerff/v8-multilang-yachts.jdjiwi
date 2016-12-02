<?php


class _pages_main_edit_modul extends driver_modul_edit_tree {

	protected function init() {
		parent::init();

		$this->setDb('_pages_main_edit_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();

		$form->add('parent',		new cmfFormSelectInt());
		$form->add('type',		    new cmfFormSelect());
		$form->add('part',		    new cmfFormSelect());


		$form->add('small_name',	new cmfFormText());
		$form->add('name',		    new cmfFormText());
		$form->add('visible',		new cmfFormCheckbox());

		if($id = $this->getId()) {
			list(, $type) = $this->getDb()->getTypeSmallName($id);
			cmfGlobal::set('pageType', $type);
		}
	}

	public function loadForm($processing=false) {
		parent::loadForm();
		$parent = $this->getFilter('parent');
		if($this->getId()) {
			$data = $this->runData();
			$parent = $data['parent'];
			$type = $data['type'];
			$smallName = $data['small_name'];
		} else {
			if($parent) {
				list($smallName, $type) = $this->getDb()->getTypeSmallName($parent);
				if($type==='list') $type = 'pages';
			} else {
				$type = 'tree';
				$smallName = '';
			}
		}
		if($type==='pagesSystem') $type = 'pages';
		cmfGlobal::set('pageType', $type);

		$form = $this->getForm();


		$form->select('small_name', $smallName .'_');
		if($type==='pages') {
			$form->addElement('type', 'pages', 			'Страница');
			$form->addElement('type', 'pagesSystem', 	'Системная страница');
			$form->addElement('type', 'list', 			'Список страниц');
			$form->addElement('type', 'tree', 			'Узел');

            $form->addElement('part', 'main', 			'main');
            $form->addElement('part', 'blog', 			'blog');


			//$form->add('part',			new cmfFormSelect());
			//$form->addElement('part', 'main', 'main');

			$form->add('url',				new cmfFormText());
			$form->add('pattern',			new cmfFormText());
			if($processing) {
				$form->add('php_path',	new cmfFormText());
			} else {
				$form->add('php_path',	new cmfFormText(array('1empty'=>1)));
			}
			$form->add('variables',		new cmfFormText());

			$form->add('templates',		new cmfFormSelect());
			$form->addElement('templates', '', 'не выбрано');
			foreach(cmfDir::getList(cmfTeplates) as $file) {
                $form->addElement('templates', $file, $file);
			}

			if($type==='pages') {
				$form->add('php_path',	new cmfFormText(array('1empty'=>1)));
			} else {
				$form->add('php_path',	new cmfFormText());
			}

			$form->add('cacheBrousers',	new cmfFormCheckbox(array('label'=>'Кешировать Браузерами')));

			$form->add('cache',			new cmfFormCheckbox(array('label'=>'Кешировать')));
			$form->select('cache','yes');
			$form->add('cacheMain',		new cmfFormCheckbox(array('label'=>'в зависимости от родителькой страницы')));
			$form->add('cacheUrl',		new cmfFormCheckbox(array('label'=>'в зависимости от адреса страницы')));
			$form->add('cacheRequestUri',	new cmfFormCheckbox(array('label'=>'в зависимости от параметров адресной строки')));

			$smallName = str_replace('_', '/', $smallName);
			if(strpos($smallName, '/')!==0) $uri = '/'. $smallName;
			else $uri = $smallName;
			$form->select('php_path', $smallName .'/');
		} else {

			$form->addElement('type', 'tree', 			'Узел');
			$form->addElement('type', 'list', 			'Список страниц');
			$form->addElement('type', 'pages', 			'Страница');
			$form->addElement('type', 'pagesSystem', 	'Системная страница');
		}
	}


	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(isset($send['type'])) {
			$this->setNewView();
		}
	}

}

?>