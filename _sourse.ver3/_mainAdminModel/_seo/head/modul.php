<?php

class _seo_head_modul extends driver_modul_list {

    protected function init() {
        parent::init();

        $this->setDb('_seo_head_db');

        // формы
        $form = $this->getForm();
        $form->add('url', new cmfFormText(array('max' => 150)));
        $form->add('head', new cmfFormTextarea());
        $form->add('type', new cmfFormSelect());
        $form->add('visible', new cmfFormCheckbox());
    }

    public function loadForm() {
        $form = $this->getForm();

        $form->addElement('type', 'main', 'www');
        $form->addElement('type', 'blog', 'blog');
    }

    protected function saveStart(&$send) {
        parent::saveStart($send);
    }

}

?>