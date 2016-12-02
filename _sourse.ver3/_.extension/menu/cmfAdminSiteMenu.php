<?php


class cmfAdminSiteMenu {

    static public function initSiteListMenu(&$form) {
        $form->addElement('menu', array('------', 'index'),          'Главная');
//        $form->addElement('menu', array('------', 'contact'),        'Контакты');
        $form->addElement('menu', array('------', 'adress'),	     'Ссылка');

        self::initSiteMenu($form);
	}


    static public function initSiteSaleMenu(&$form) {
        $form->addElement('menu', array('Продажа яхт', 'sale/new'),	        'Каталог новых яхт');
        $form->addElement('menu', array('Продажа яхт', 'sale/new/search'),	'Подбор яхты');
        $form->addElement('menu', array('Продажа яхт', 'brokerage'),	    'Брокераж');
        $form->addElement('menu', array('Продажа яхт', 'request/sale'),	'Запрос на подбор яхты');
        $form->addElement('menu', array('Продажа яхт', 'adress'),	        'Ссылка');

		self::initSiteMenu($form);
	}

    static public function initSiteArendaMenu(&$form) {
        $form->addElement('menu', array('Аренда', 'arenda'),	            'Аренда');
        $form->addElement('menu', array('Аренда', 'arenda/charter'),	    'Международный чартер');
        $form->addElement('menu', array('Аренда', 'request/arenda'),	    'Запрос на подбор яхты');
        $form->addElement('menu', array('Аренда', 'adress'),	            'Ссылка');

        $name = cmfModulLoad('arenda_list_db')->getNameList(array('level<3'));
        foreach($name as $key=>$value)
            $form->addElement('menu', array('Аренда ', $key .'arenda'), $value['name']);

		self::initSiteMenu($form);
	}

    static public function initSiteUsefulMenu(&$form) {
        $form->addElement('menu', array('Продажа яхт', 'shipyards'),	'Все верфи мира');

		$form->addElement('menu', array('Полезное', 'news'),	    'Новости');
		$form->addElement('menu', array('Полезное', 'article'),	    'Статьи и Обзоры');
		$form->addElement('menu', array('Полезное', 'foto'),	    'Фотографии');
		$form->addElement('menu', array('Полезное', 'wallpapers'),  'Обои');
		$form->addElement('menu', array('Полезное', 'adress'),	    'Адресс');


		$form->addElement('menu', array('Полезное', 'map'),	        'Карта сайта');
		$form->addElement('menu', array('Полезное', 'contact'),    	'Контакты');
		self::initSiteMenu($form);
	}

    static public function initSiteMenu(&$form) {
        $name = cmfModulLoad('arenda_list_db')->getNameList(array('level<3'));
        foreach($name as $key=>$value)
            $form->addElement('menu', array('Аренда ', $key .'arenda'), $value['name']);

		$name = cmfModulLoad('menu_info_list_db')->getNameList();
        foreach($name as $key=>$value)
            $form->addElement('menu', array('Информация ', $key .'menu'), $value['name']);

        $name = cmfModulLoad('content_list_db')->getNameList();
        foreach($name as $key=>$value)
            $form->addElement('menu', array('Контент', $key .'content'), $value['name']);
    }

 	public static function initParamMenu() {
		$param = array();
		$param[''] = 'Отсуствует';
		foreach(cmfModulLoad('param_list_db')->getNameList() as $k=>$v) {
            $param[$k] = $v['name'];
		}
		return $param;
	}


 	public static function initCurrency() {
		$param = array();
		$param[''] = 'Не выбрано';
		$param['EURO'] = 'EURO';
		$param['USD'] = 'USD';
		$param['RUR'] = 'RUR';
		$param['UAH'] = 'UAH';
		return $param;
	}


 	public static function initParamBasketMenu() {
		$param = array();
		$param[''] = 'Отсуствует';
		foreach(cmfModulLoad('param_list_db')->getNameList(array('type'=>'basket')) as $k=>$v) {
            $param[$k] = $v['name'];
		}
		return $param;
	}

}

?>
