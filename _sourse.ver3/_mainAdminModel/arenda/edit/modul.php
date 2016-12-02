<?php

class arenda_edit_modul extends driver_modul_edit_tree {

    protected function init() {
        parent::init();

        $this->setDb('arenda_edit_db');

        // формы
        $form = $this->getForm();
        $this->setNewPos();

        cmfGlobal::set('$isEdit', $isEdit = (arendaMenu != $this->getId()));

        $form->add('parent', new cmfFormSelectInt());
        $form->add('uri', new cmfFormText(array('uri' => 25)));
//        $form->add('name', new cmfFormTextarea(array('!empty', 'max' => 100)));
//        $form->add('content', new cmfFormTextareaWysiwyng('arenda', $this->getId()));
        $form->add('visible', new cmfFormCheckbox());

        $form->add('phone', new cmfFormText());
        $form->add('type', new cmfFormSelect());
        if ($isEdit) {
            $form->add('flash', new cmfFormFile(array('path' => cmfPathArenda)));
            $form->add('flashXml', new cmfFormTextarea());
//        $form->add('flashContent', new cmfFormTextareaWysiwyng('arenda', $this->getId()));
            $form->add('yachtsListImage', new cmfFormFile(array('path' => cmfPathArenda)));
//        $form->add('yachtsListHeader', new cmfFormText());
//        $form->add('yachtsListUrl', new cmfFormText());
        }

        $form->add('url', new cmfFormText());
        $form->add('isUrl', new cmfFormCheckbox());

//        $form->add('title', new cmfFormTextarea());
//        $form->add('keywords', new cmfFormTextarea());
//        $form->add('description', new cmfFormTextarea());
//
//        $form->add('title2', new cmfFormTextarea());
//        $form->add('keywords2', new cmfFormTextarea());
//        $form->add('description2', new cmfFormTextarea());
    }

    public function loadForm() {
        parent::loadForm();

        $form = $this->getForm();
//        if (cmfGlobal::get('$isEdit')) {
            $form->addElement('type', 'none', 'Отсуствует');
            //$form->addElement('type', 'info', 'Информация');
            $form->addElement('type', 'map', 'Флеш карта');
            $form->addElement('type', 'yachts', 'Список яхт');
//        }
    }

    protected function updateIsErrorData($data, &$isError) {
        $parent = $this->getForm()->handlerElement('parent');
//        if (!$parent and isset($data['uri'])) {
//            $isError |= $this->updateIsErrorDataUri('/arenda/', $data['uri'], array('arenda_edit_lang_modul', 'name'), 25, array('parent' => $parent));
//        }
    }

    protected function saveStart(&$send) {
        parent::saveStart($send);
        if (!$this->getId()) {
            $send['date'] = date('Y-m-d H:i:s');
        }

        $parent = $this->getForm()->handlerElement('parent');
        parent::saveStartUri($send, array('arenda_edit_lang_modul', 'name'), 25, array('parent' => $parent));
    }

}

?>