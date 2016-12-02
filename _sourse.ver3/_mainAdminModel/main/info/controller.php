<?php


class main_info_controller extends driver_controller_lang_edit {

	function __construct($id=null) {
		$this->setIdName('main');
		parent::__construct($id);
	}

    public function configSiteUrl($page) {
        switch($page) {
            case 'main':
                return parent::viewSiteUrl('/index/');

            case 'article':
            case 'news':
            case 'wallpapers':
            case 'foto':
            case 'contact':

            case 'search':
            case 'map':
                return parent::viewSiteUrl('/'. $page .'/', 'new');

            case 'sale/new':
            case 'request/sale':
            case 'shipyards':
			case 'shipyards/search':

            case 'brokerage':

            case 'arenda/info':
            case 'charter':
            case 'charter/yachts':
			case 'request/arenda':
				return parent::viewSiteUrl('/'. $page .'/');

			case 'shipyards/search':
                return parent::viewSiteUrl('/hipyards/search/result/');

			case 'sale/new/type':
				$uri = cmfModulLoad('shipyards_type_edit_db')->getFeildOfId('uri', cmfAdminMenu::getSubMenuId());
				return parent::viewSiteUrl2('/sale/new/type/', $uri);

			case 'info':
				$uri = cmfModulLoad('menu_info_edit_db')->getFeildOfId('isUri', cmfAdminMenu::getSubMenuId());
				return parent::viewSiteUrl2('/info/', $uri);

			case 'content':
				$uri = cmfModulLoad('content_edit_db')->getFeildOfId('isUri', cmfAdminMenu::getSubMenuId());
				return parent::viewSiteUrl2('/content/', $uri);

			case 'news/edit':
				$uri = cmfModulLoad('news_edit_db')->getFeildOfId('uri', cmfAdminMenu::getSubMenuId());
				return parent::viewSiteUrl2('/news/one/', $uri);

			case 'article/edit':
				$uri = cmfModulLoad('article_edit_db')->getFeildOfId('uri', cmfAdminMenu::getSubMenuId());
				return parent::viewSiteUrl2('/article/one/', $uri);

			case 'shipyards/edit':
				$uri = cmfModulLoad('shipyards_edit_db')->getFeildOfId('uri', cmfAdminMenu::getSubMenuId());
				return parent::viewSiteUrl2('/shipyards/one/', $uri);

 			case 'arenda/edit':
				$uri = cmfModulLoad('arenda_edit_db')->getFeildOfId('uri', cmfAdminMenu::getSubMenuId());
				return parent::viewSiteUrl2('/arenda/one/', $uri);

            case 'contact/main':
                return parent::viewSiteUrl('/contact/');
		}
    }
    public function viewSiteUrl() {
        return $this->configSiteUrl($this->getId());
    }

	protected function init() {
		parent::init();
		$this->addModul('main',		'main_info_modul');
		$this->addModul('lang',	    'main_info_lang_modul');

		// url
		$this->setSubmitUrl(cmfPages::getMain());
	}

	public function getFlashParamList() {

	    $res = cmfModulLoad('arenda_list_db')->getDataList(array(1));
	    $list = array();
	    foreach($res as $k=>$v) {
            $list[$v['parent']][$k] = array('name'=>$v['name'],
                                                  'image'=>!empty($v['yachtsListImage']) ? '/'. cmfPathArenda . $v['yachtsListImage'] : '',
                                                  'url'=>'/'. $v['isUri'] .'/', cmfGetUrl('/arenda/', array($v['isUri'])));
	    }
	    return $list;
	}
}

?>