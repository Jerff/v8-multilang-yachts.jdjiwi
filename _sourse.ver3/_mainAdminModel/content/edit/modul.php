<?php

class content_edit_modul extends driver_modul_edit_tree {

    protected function init() {
        parent::init();

        $this->setDb('content_edit_db');

        // формы
        $form = $this->getForm();
        $this->setNewPos();

        $form->add('parent', new cmfFormSelectInt());
        $form->add('pages', new cmfFormSelect());

        $form->add('uri', new cmfFormText(array('uri' => 45)));

        $form->add('image', new cmfFormFile(array('path' => cmfPathContent)));
        $form->add('visible', new cmfFormCheckbox());

        $form->add('request', new cmfFormSelect());

        $form->add('url', new cmfFormText());
        $form->add('isUrl', new cmfFormCheckbox());
    }

    protected function updateIsErrorData($data, &$isError) {
        $parent = $this->getForm()->handlerElement('parent');
        if (!$parent and isset($data['uri'])) {
            $isError |= $this->updateIsErrorDataUri('/content/', $data['uri'], array('content_edit_lang_modul', 'name'), 25, array('parent' => $parent));
        }
    }

    public function loadForm() {
        parent::loadForm();
        $form = $this->getForm();

        $form->addElement('request', 'none', 'Не выбрано');
        $form->addElement('request', 'arenda', 'Аренду');
        $form->addElement('request', 'sale', 'Продажу');

        $form->addElement('pages', 'default', 'Обычная страница');
//        $form->addElement('pages', 'new_sale', 'Новые яхты');
//        $form->addElement('pages', 'brokerage', 'Брокерэдж');
//        $form->addElement('pages', '1', '---------');
//        $form->addElement('pages', 'news', 'Новости');
//        $form->addElement('pages', 'articles', 'Статьи');
//        $form->addElement('pages', 'reviews', 'Отзывы');
//        $form->addElement('pages', 'photo', 'Фото');
//        $form->addElement('pages', 'wallpapers', 'Обои');
    }

    protected function saveStart(&$send) {
        parent::saveStart($send);

        $parent = $this->getForm()->handlerElement('parent');
        parent::saveStartUri($send, array('content_edit_lang_modul', 'name'), 25, array('parent' => $parent));
    }

}

?>