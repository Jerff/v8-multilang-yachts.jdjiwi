<?php

class menu_rigth_controller extends driver_controller_list_all_lang {

    protected function init() {
        parent::init();
        $this->addModul('main', 'menu_rigth_modul');
        $this->addModul('lang', 'menu_rigth_lang_modul');

        // url
        $this->setSubmitUrl('/admin/menu/rigth/');

        $this->callFuncWriteAdd('newLine');
    }

    public function delete($id) {
        parent::deleteList($id);
        return parent::delete($id);
    }

    public function filterSection() {
//        $filter = array(
//            'default' => 'Обычная страница',
//            '1' => '---------',
//            'arenda' => 'Аренда',
//            'arenda-menu' => 'Информация по аренде',
//            'arenda-ukraine' => 'Аренда яхт в Украине',
//            'charter' => 'Международный чартер',
//            'new_sale' => 'Новые яхты',
//            'shipyards' => 'Верфи',
//            'brokerage' => 'Брокерэдж',
//            '2' => '---------',
//            'form.sale' => 'Запрос на продажу',
//            'form.arenda' => 'Запрос на аренду',
//            '3' => '---------',
//            'contact' => 'Контакты',
//            'news' => 'Новости',
//            'articles' => 'Статьи',
//            'reviews' => 'Отзывы',
//            'board' => 'Доска объявлений',
//            'search' => 'Поиск',
//            'searchYachts' => 'Поиск яхт',
//            '4' => '---------',
//            'photo' => 'Фото',
//            'wallpapers' => 'Обои',
//        );
//        foreach ($filter as $key => $value) {
//            $filter[$key] = array('name'=>$value);
//        }
        $filter = cmfModulLoad('menu_pages_db')->getNameList();
        return parent::abstractFilterPart($filter, 'section', 'reset');
    }

}

?>