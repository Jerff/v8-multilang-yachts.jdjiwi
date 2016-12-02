<?php


class shipyards_yachts_edit_modul extends driver_modul_gallery_edit {

	protected function init() {
		parent::init();

		$this->setDb('shipyards_yachts_edit_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();

		$form->add('shipyards',		new cmfFormSelectInt(array('!empty')));
		$form->add('type',		    new cmfFormRadioInt(array('!empty')));

		$form->add('uri',			new cmfFormText(array('uri'=>100)));
//		$form->add('name',		    new cmfFormTextarea(array('max'=>255, '!empty')));
//		$form->add('notice',		new cmfFormTextareaWysiwyng('shipyards_yachts', $this->getId(), array('max'=>10000)));
		$form->add('price',	        new cmfFormTextInt());
		$form->add('currency',	    new cmfFormSelect());


		$size=array();
		$size['main'] = array(yachtsWidth, yachtsHeight);
		$size['small'] = array(yachtsSmallWidth, yachtsSmallHeight);
		$size['best'] = array(yachtsBestWidth, yachtsBestHeight);
		$form->add('image',	        new cmfFormImage(array('name'=>'image', 'path'=>cmfPathShipyardsYachts, 'size'=>$size, 'addField')));
//		$form->add('image_title',	new cmfFormText(array('max'=>255, '1!empty')));

//		$form->add('title',			new cmfFormTextarea(array('max'=>255)));
//		$form->add('keywords',		new cmfFormTextarea(array('max'=>255)));
//		$form->add('description',	new cmfFormTextarea(array('max'=>255)));

		$form->add('catalog',	        new cmfFormCheckbox());
		$form->add('visible',	        new cmfFormCheckbox());


		$this->setPath(cmfPathShipyardsYachts);
		$this->setSize(yachtsSmallWidth, yachtsSmallHeight);
	}


/*    protected function updateIsErrorData($data, &$isError) {
        if(!$this->getId() and !isset($data['image'])) {
            $isError = true;
            $this->getForm()->setError('image', 'Выберите файл');
        }
        return $isError;
    }*/

	protected function updatePreviewAll($res, $data, $width, $height) {
         parent::updatePreviewAll($res, $data, $width, $height);
         $this->updateThumbnail($res, $data['image_best'], yachtsBestWidth, yachtsBestHeight);
	}

	public function loadForm() {
		parent::loadForm();

		$form = $this->getForm();
		$name = cmfModulLoad('shipyards_list_db')->getNameList();
		$form->addElement('shipyards', 0, 'Отсуствует');
		foreach($name as $k=>$v) {
            $form->addElement('shipyards', $k, $v['name']);
		}
		$form->select('shipyards', $this->getFilter('shipyards'));

        $name = cmfModulLoad('shipyards_type_list_db')->getNameList();
		$form->addElement('type', 0, 'Отсуствует');
		foreach($name as $k=>$v) {
            $form->addElement('type', $k, $v['name']);
		}

        foreach(cmfAdminSiteMenu::initCurrency() as $k=>$v) {
            $form->addElement('currency', $k, $v);
		}
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(isset($send['pos']) and (empty($send['pos']) or $send['pos']>99)) {
				$send['pos'] = 99;
		}

		if(!$this->getId()) {
			 $send['date'] = date('Y-m-d H:i:s');
		}
        $send['update'] = time();
        
		$shipyards = $this->getForm()->handlerElement('shipyards');
		parent::saveStartUri($send, 'name', 100, array('shipyards'=>$shipyards));
	}

}

?>