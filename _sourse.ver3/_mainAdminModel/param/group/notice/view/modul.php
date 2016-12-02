<?php


class param_group_notice_view_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('param_group_notice_view_db');
		// формы
		$form = $this->getForm();
		switch(cmfPages::getParam(1)) {
		    case 'shipyards':
        		$form->add('shipyardsParam',	new cmfFormSelectInt());
        		$form->add('shipyardsPhone',	new cmfFormText());
        		$form->addElement('shipyardsParam', 0, 'Отсуствует');
        		foreach(cmfModulLoad('param_list_db')->getNameList() as $k=>$v) {
                    $form->addElement('shipyardsParam', $k, $v['name']);
        		}
		        break;

		    case 'board':
        		$form->add('boardParam',	new cmfFormSelectInt());
        		$form->add('boardPhone',	new cmfFormText());
        		$form->addElement('boardParam', 0, 'Отсуствует');
        		foreach(cmfModulLoad('param_list_db')->getNameList() as $k=>$v) {
                    $form->addElement('boardParam', $k, $v['name']);
        		}

        		$form->add('boardParam2',	new cmfFormSelectInt());
        		$form->addElement('boardParam2', 0, 'Отсуствует');
        		foreach(cmfModulLoad('param_list_db')->getNameList() as $k=>$v) {
                    $form->addElement('boardParam2', $k, $v['name']);
        		}
		        break;

		    case 'brokerage':
        		$form->add('brokerageParam',	new cmfFormSelectInt());
        		$form->add('brokeragePhone',	new cmfFormText());
        		$form->addElement('brokerageParam', 0, 'Отсуствует');
        		foreach(cmfModulLoad('param_list_db')->getNameList() as $k=>$v) {
                    $form->addElement('brokerageParam', $k, $v['name']);
        		}

        		$form->add('brokerageShowcaseParam',	new cmfFormSelectInt());
        		$form->addElement('brokerageShowcaseParam', 0, 'Отсуствует');
        		foreach(cmfModulLoad('param_list_db')->getNameList() as $k=>$v) {
                    $form->addElement('brokerageShowcaseParam', $k, $v['name']);
        		}
		        break;

		    case 'arenda':
        		$form->add('arendaParam1',	new cmfFormSelectInt());
        		$form->add('arendaPhone',	new cmfFormText());
        		$form->addElement('arendaParam1', 0, 'Отсуствует');
        		foreach(cmfModulLoad('param_list_db')->getNameList() as $k=>$v) {
                    $form->addElement('arendaParam1', $k, $v['name']);
        		}

        		$form->add('arendaParam2',	new cmfFormSelectInt());
        		$form->addElement('arendaParam2', 0, 'Отсуствует');
        		foreach(cmfModulLoad('param_list_db')->getNameList() as $k=>$v) {
                    $form->addElement('arendaParam2', $k, $v['name']);
        		}

        		$form->add('arendaParam3',	new cmfFormSelectInt());
        		$form->addElement('arendaParam3', 0, 'Отсуствует');
        		foreach(cmfModulLoad('param_list_db')->getNameList() as $k=>$v) {
                    $form->addElement('arendaParam3', $k, $v['name']);
        		}
		        break;
		}
	}

}

?>