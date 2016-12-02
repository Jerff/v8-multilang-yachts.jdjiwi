<?php

class menu_info_edit_modul extends driver_modul_edit_tree {

    protected function init() {
        parent::init();

        $this->setDb('menu_info_edit_db');

        // формы
        $form = $this->getForm();
        $this->setNewPos();

        $form->add('parent', new cmfFormSelectInt());
        $form->add('pages', new cmfFormSelect());

        $form->add('uri', new cmfFormText(array('uri' => 45)));

        $form->add('image', new cmfFormFile(array('path' => cmfPathInfo)));
        $form->add('visible', new cmfFormCheckbox());

        $form->add('request', new cmfFormSelect());

        $form->add('url', new cmfFormText());
        $form->add('isUrl', new cmfFormCheckbox());
    }

    protected function updateIsErrorData($data, &$isError) {
        $parent = $this->getForm()->handlerElement('parent');
        if (!$parent and isset($data['uri'])) {
            $isError |= $this->updateIsErrorDataUri('/info/', $data['uri'], array('menu_info_edit_lang_modul', 'name'), 45, array('parent' => $parent));
        }
    }

    public function loadForm() {
        parent::loadForm();
        $form = $this->getForm();

        $form->addElement('request', '', 'Не выбрано');
        $form->addElement('request', 'arenda', 'Аренду');
        $form->addElement('request', 'sale', 'Продажу');

        $name = cmfModulLoad('menu_pages_db')->getNameList();
		foreach($name as $k=>$v) {
            $form->addElement('pages', $k, $v['name']);
		}

//        $form->addElement('pages', 'default', 'Обычная страница');
//        $form->addElement('pages', '1', '---------');
//        $form->addElement('pages', 'arenda', 'Аренда');
//        $form->addElement('pages', 'arenda-menu', 'Информация по аренде');
//        $form->addElement('pages', 'arenda-ukraine', 'Аренда яхт в Украине');
//        $form->addElement('pages', 'charter', 'Международный чартер');
//        $form->addElement('pages', 'new_sale', 'Новые яхты');
//        $form->addElement('pages', 'shipyards', 'Верфи');
//        $form->addElement('pages', 'brokerage', 'Брокерэдж');
//        $form->addElement('pages', '2', '---------');
//        $form->addElement('pages', 'form.sale', 'Запрос на продажу');
//        $form->addElement('pages', 'form.arenda', 'Запрос на аренду');
//        $form->addElement('pages', '3', '---------');
//        $form->addElement('pages', 'contact', 'Контакты');
//        $form->addElement('pages', 'news', 'Новости');
//        $form->addElement('pages', 'articles', 'Статьи');
//        $form->addElement('pages', 'reviews', 'Отзывы');
//        $form->addElement('pages', 'board', 'Доска объявлений');
//        $form->addElement('pages', 'search', 'Поиск');
//        $form->addElement('pages', 'searchYachts', 'Поиск яхт');
//        $form->addElement('pages', '4', '---------');
//        $form->addElement('pages', 'photo', 'Фото');
//        $form->addElement('pages', 'wallpapers', 'Обои');
    }

    protected function saveStart(&$send) {
        parent::saveStart($send);

        $parent = $this->getForm()->handlerElement('parent');
        parent::saveStartUri($send, array('menu_info_edit_lang_modul', 'name'), 25, array('parent' => $parent));
    }

}

?>