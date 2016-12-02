<?php


class cmfCronConfig {

    static public function menu() {
        $menu = array();
        $menu['updateCache']   = 'Обновление кеша';
        $menu['siteMap']       = 'Генерация siteMap';
        $menu['updateSearch']  = 'Обновление поискового индекса';
        $menu['backup']        = 'Резервное копирование';
		return $menu;
    }


    static public function runModul($name) {
        switch($name) {
            case 'updateCache':
                cmfCronCacheUpdate::start();
                break;

            case 'siteMap':
                cmfCronSiteMap::run();
                break;

            case 'updateSearch':
				cmfCronUpdateSearch::init();
				break;

			case 'subscribe':
				cmfCronSubscribe::run();
				break;

			case 'backup':
				cmfCronBackup::run();
				break;

			case 'Yandex.Market':
				cmfCronYandexMarket::run();
				break;

			case 'user.activate':
				cmfCronClearUserActivate::run();
				break;
		}
	}

}

?>
