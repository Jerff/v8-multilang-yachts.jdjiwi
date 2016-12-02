<?php


class blog_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('blog_edit_db');

		// формы
		$form = $this->getForm();
        $form->add('date',          new cmfFormTextDateTime());
		$form->add('autor',		    new cmfFormSelectInt(array('!empty')));

		$form->add('uri',			new cmfFormText(array('uri'=>100)));
//		$form->add('name',		    new cmfFormTextarea(array('max'=>255, '!empty')));
//		$form->add('notice',		new cmfFormTextareaWysiwyng('blog', $this->getId(), array('max'=>10000)));
//		$form->add('content',		new cmfFormTextareaWysiwyng('blog', $this->getId(), array('max'=>10000)));
//
//
//		$form->add('title',			new cmfFormTextarea(array('max'=>255)));
//		$form->add('keywords',		new cmfFormTextarea(array('max'=>255)));
//		$form->add('description',	new cmfFormTextarea(array('max'=>255)));

		$form->add('main',          new cmfFormCheckbox());
		$form->add('visible',	    new cmfFormCheckbox());

        $form->add('image',	new cmfFormFile(array('path'=>cmfPathBlog)));
	}

	public function loadForm() {
		parent::loadForm();

		$form = $this->getForm();
        $name = cmfModulLoad('blog_autor_list_db')->getNameList();
		$form->addElement('autor', 0, 'Отсуствует');
		foreach($name as $k=>$v) {
            $form->addElement('autor', $k, $v['name']);
		}
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
        $send['update'] = time();

		parent::saveStartUri($send, 'name', 100);
	}

}

?>