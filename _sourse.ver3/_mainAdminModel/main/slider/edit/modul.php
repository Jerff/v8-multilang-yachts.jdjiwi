<?php


class main_slider_edit_modul extends driver_modul_gallery_list_edit {

	protected function init() {
		parent::init();

		$this->setDb('main_slider_edit_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();

//		$form->add('name',		    new cmfFormText(array('max'=>255, '1!empty')));
//        $form->add('notice',        new cmfFormTextareaWysiwyng('main', cmfAdminMenu::getSubMenuId()));
		$form->add('link',		    new cmfFormText(array('max'=>255, '1!empty')));
		$form->add('main',		    new cmfFormCheckbox());
		$form->add('visible',		new cmfFormCheckbox());

		$form->add('image',	        new cmfFormFile(array('name'=>'image', 'path'=>cmfPathMain)));
        if(cmfAdminMenu::getSubMenuId() == 'main') {
            $form->add('video', new cmfFormText());
            $form->add('video_image',	        new cmfFormFile(array('name'=>'image', 'path'=>cmfPathMain)));
    		$form->add('video_style',		    new cmfFormText(array('max'=>255, '1!empty')));
        }
	}

	protected function saveStart(&$send) {
		if(count($send) and !$this->getId()) {
			 $send['page'] = $this->getFIlter('pageData');
			 $send['parent'] = cmfAdminMenu::getSubMenuId();
		}
        if(isset($send['main']) or $this->getId()) {
            $this->setNewView();
        }
		parent::saveStart($send);
	}

}

?>