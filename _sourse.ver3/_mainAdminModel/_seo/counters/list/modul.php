<?php

class _seo_counters_list_modul extends driver_modul_list {

    protected function init() {
        parent::init();

        $this->setDb('_seo_counters_list_db');

        // �����
        $form = $this->getForm();
        $this->setNewPos();
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

    protected function saveStart(&$send) {
        parent::saveStart($send);
        if (isset($send['main']) or isset($send['type'])) {
            $this->setNewView();
        }
    }

}

?>