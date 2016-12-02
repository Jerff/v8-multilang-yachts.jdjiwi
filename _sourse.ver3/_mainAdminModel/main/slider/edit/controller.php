<?php


class main_slider_edit_controller extends driver_controller_lang_edit {

	protected function init() {
		parent::init();
		$this->addModul('main',	'main_slider_edit_modul');
        $this->addModul('lang',	'main_slider_edit_lang_modul');

		// url
		$this->setSubmitUrl(cmfPages::getMain());
		$this->setCatalogUrl(cmfPages::getMain());
	}

    public function viewSiteUrl() {
        return cmfModulLoad('main_info_controller')->configSiteUrl($this->getFIlter('pageData'));
    }

	public function deleteSlider($page, $id) {
		$old = cmfAdminMenu::getSubMenuId();
		cmfAdminMenu::setSubMenuId($id);
		$old2 = $this->getFilter('pageData');
		$this->setFilter('pageData', $page);
		$this->delete($this->getListId(array('page'=>$page, 'AND', 'parent'=>$id)));
		cmfAdminMenu::setSubMenuId($old);
		$old2 = $this->setFilter('pageData', $old2);
	}

}

?>