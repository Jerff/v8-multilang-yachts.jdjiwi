<?php

class arenda_type_edit_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('arenda_type_edit_db');

        // формы
        $form = $this->getForm();

        $this->setNewPos();
        $form->add('uri', new cmfFormText(array('uri' => 25)));
//        $form->add('name', new cmfFormTextarea(array('!empty', 'max' => 100)));
        $form->add('visible', new cmfFormCheckbox());
    }

    protected function saveStart(&$send) {
        parent::saveStart($send);

        parent::saveStartUri($send, 'name', 25);
    }

}

?>