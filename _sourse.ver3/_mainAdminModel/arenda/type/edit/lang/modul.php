<?php

class arenda_type_edit_lang_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('arenda_type_edit_lang_db');

        // формы
        $form = $this->getForm();
        $form->add('name', new cmfFormTextarea(array('!empty', 'max' => 100)));
    }

}

?>