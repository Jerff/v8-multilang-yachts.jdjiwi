<?php


class main_showcase_modul extends driver_modul_list {

    function __construct($id=null) {
        $this->setIdName('image');
        parent::__construct($id);
	}

	protected function init() {
		parent::init();

		$this->setDb('main_showcase_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();
		$form->add('page',		new cmfFormSelect());
		$form->add('type',		    new cmfFormSelectInt());
		$form->add('yachts',		new cmfFormSelectInt());

        $form->add('visible',		new cmfFormCheckbox());
	}


    public function loadForm() {
        $form = $this->getForm();
        $form->addElement('page', '', 'Не выбрано');
        $form->addElement('page', 'arenda', 'Аренда');
        $form->addElement('page', 'sale', 'Новые яты');
        $form->addElement('page', 'brokerage', 'Брокерэдж');
        $form->select('page', 'arenda');
    }

    public function loadForm2($id=null) {
        if(!$id) $id = $this->getId();

        $form = $this->getForm();
        $form->resetElement('type');
        $form->addElement('type', 0, 'Не выбрано');
        $form->resetElement('yachts');
        $form->addElement('yachts', 0, 'Не выбрано');

        $data = $this->getDataId($id);
        $page = get($data, 'page');
        $type = get($data, 'type');
        $yachts = get($data, 'yachts');
        switch ($page) {
            case 'arenda':
                $mType = cmfModulLoad('arenda_type_list_db')->getNameList();
                $mYachts = cmfModulLoad('arenda_yachts_list_db')->getNameList(array('type'=>$type));
                break;
            case 'sale':
                $mType = cmfModulLoad('shipyards_type_list_db')->getNameList();
                $mYachts = cmfModulLoad('arenda_yachts_list_db')->getNameList(array('type'=>$type));
                break;
            case 'brokerage':
                $mType = cmfModulLoad('brokerage_type_list_db')->getNameList();
                $mYachts = cmfModulLoad('brokerage_yachts_list_db')->getNameList(array('type'=>$type));
                break;
            default:
                return;
                break;
        }

        foreach($mType as $key=>$value) {
            $form->addElement('type', $key, $value['name']);
        }
        $form->select('type', $type);

        foreach($mYachts as $key=>$value) {
            $form->addElement('yachts', $key, $value['name']);
        }
        $form->select('yachts', $yachts);
    }


    public function onchangePage($id) {
        $form = $this->getForm();
        $form->changeName($this->getNameID($id));
        $page = $form->handlerElement('page');
        $type = $form->handlerElement('type');

        switch ($page) {
            case 'arenda':
                $mType = cmfModulLoad('arenda_type_list_db')->getNameList();
                $yachts = cmfModulLoad('arenda_yachts_list_db')->getNameList(array('type'=>$type));
                break;
            case 'sale':
                $mType = cmfModulLoad('shipyards_type_list_db')->getNameList();
                $yachts = cmfModulLoad('arenda_yachts_list_db')->getNameList(array('type'=>$type));
                break;
            case 'brokerage':
                $mType = cmfModulLoad('brokerage_type_list_db')->getNameList();
                $yachts = cmfModulLoad('brokerage_yachts_list_db')->getNameList(array('type'=>$type));
                break;
            default:
                return;
                break;
        }

        $form->resetElement('type');
        $form->addElement('type', 0, 'Не выбрано');
        foreach($mType as $key=>$value) {
            $form->addElement('type', $key, $value['name']);
        }
        $form->select('type', $type);
		$this->getResponse()->addScript($form->get('type')->jsUpdate());

        $form->resetElement('yachts');
        $form->addElement('yachts', 0, 'Не выбрано');
        foreach($yachts as $key=>$value) {
            $form->addElement('yachts', $key, $value['name']);
        }
		$this->getResponse()->addScript($form->get('yachts')->jsUpdate());
	}

}

?>