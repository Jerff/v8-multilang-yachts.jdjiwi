<?php


class cmfSiteMapConfig {

    static public function menu() {
        $menu = array();
        $menu['index']   = 'Главная';
        $menu['news & article']    = 'Новости & Статьи';
        $menu['foto & wallpapers'] = 'Фотографии & Обои';
        $menu['contact'] = 'Контакты';
        $menu['arenda'] = 'Аренда';
        $menu['brokerage'] = 'Брокераж';
        $menu['sale/new'] = 'Продажа новых яхт';
        $menu['shipyards'] = 'Верфи';
        $menu['info']    = 'Информация';
		return $menu;
    }

}

?>
