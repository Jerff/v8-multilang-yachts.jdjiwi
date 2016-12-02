<?php

class main_slider_edit_lang_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('main_slider_edit_lang_db');

        // формы
        $form = $this->getForm();
        $form->add('name', new cmfFormText(array('max' => 255, '1!empty')));
        $form->add('notice', new cmfFormTextareaWysiwyng('main', cmfAdminMenu::getSubMenuId()));
    }

    protected function saveStart(&$send) {
        if (isset($send['name'])) {
            $this->setNewView();
        }
        parent::saveStart($send);
    }

}

?>