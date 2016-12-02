<?php

class arenda_edit_lang_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('arenda_edit_lang_db');

        // формы
        $form = $this->getForm();
//        $form->add('parent', new cmfFormSelectInt());
//        $form->add('uri', new cmfFormText(array('uri' => 25)));
        $form->add('name', new cmfFormTextarea(array('!empty', 'max' => 100)));
        $form->add('name_search', new cmfFormText(array('!empty', 'max' => 100)));

        if (cmfGlobal::get('$isEdit')) {

            $form->add('content', new cmfFormTextareaWysiwyng('arenda', $this->getId()));
//        $form->add('visible', new cmfFormCheckbox());
//        $form->add('type', new cmfFormSelect());
//        $form->add('flash', new cmfFormFile(array('path' => cmfPathArenda)));
//        $form->add('flashXml', new cmfFormTextarea());
            $form->add('flashContent', new cmfFormTextareaWysiwyng('arenda', $this->getId()));
//        $form->add('yachtsListImage', new cmfFormFile(array('path' => cmfPathArenda)));
            $form->add('yachtsListHeader', new cmfFormText());
            $form->add('yachtsListUrl', new cmfFormText());

//        $form->add('url', new cmfFormText());
//        $form->add('isUrl', new cmfFormCheckbox());

            $form->add('title', new cmfFormTextarea());
            $form->add('keywords', new cmfFormTextarea());
            $form->add('description', new cmfFormTextarea());
        }

        $form->add('title2', new cmfFormTextarea());
        $form->add('keywords2', new cmfFormTextarea());
        $form->add('description2', new cmfFormTextarea());
    }

    protected function saveStart(&$send) {
        parent::saveStart($send);
    }

}

?>