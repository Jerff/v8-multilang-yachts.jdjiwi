<?php

class _seo_counters_edit_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('_seo_counters_edit_db');

        // �����
        $form = $this->getForm();
        $this->setNewPos();

        $form->add('name', new cmfFormTextarea(array('max' => 255, '!empty')));
        $form->add('counters', new cmfFormTextarea());
        $form->add('main', new cmfFormCheckbox());
        $form->add('type', new cmfFormSelect(array('!empty')));
        $form->add('visible', new cmfFormCheckbox());
    }

    public function loadForm() {
        parent::loadForm();
        $form = $this->getForm();

        $form->addElement('type', '', 'не выбрано');
        $form->addElement('type', 'head', 'head');
        $form->addElement('type', 'header', 'header');
        $form->addElement('type', 'footer', 'footer');
    }

}

?>