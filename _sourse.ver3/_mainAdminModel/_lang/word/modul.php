<?php

class _lang_word_modul extends driver_modul_list {

    protected function init() {
        parent::init();

        $this->setDb('_lang_word_db');

        // �����
        $form = $this->getForm();
        $form->add('name', new cmfFormText(array('max' => 255, '!empty')));
        $form->add('en', new cmfFormText(array('max' => 255, '!empty')));
    }

}

?>