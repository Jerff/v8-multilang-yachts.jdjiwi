<?php

class reviews_brand_modul extends driver_modul_list {

    protected function init() {
        parent::init();

        $this->setDb('reviews_brand_db');

        // формы
        $form = $this->getForm();
        $this->setNewPos();
//		$form->add('name',		new cmfFormText(array('max'=>150)));
        $form->add('image',	new cmfFormFile(array('path'=>cmfPathBrand)));
        $form->add('main', new cmfFormCheckbox());
        $form->add('visible', new cmfFormCheckbox());
    }

    protected function saveStart(&$send) {
        parent::saveStart($send);
        if (isset($send['main'])) {
            $this->setNewView();
        }
    }

}

?>