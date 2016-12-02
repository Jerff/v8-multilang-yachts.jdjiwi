<?php

class shipyards_edit_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('shipyards_edit_db');

        // формы
        $form = $this->getForm();
        $this->setNewPos();

        $form->add('uri', new cmfFormText(array('uri' => 25)));
//		$form->add('name',	    new cmfFormTextarea(array('!empty', 'max'=>100)));
//		$form->add('content',	new cmfFormTextareaWysiwyng('shipyards', $this->getId()));
        $form->add('isHeader', new cmfFormCheckbox());
        $form->add('main', new cmfFormCheckbox());
        $form->add('visible', new cmfFormCheckbox());

        $form->add('logo', new cmfFormFile(array('path' => cmfPathShipyardsLogo)));
        $form->add('image', new cmfFormFile(array('path' => cmfPathShipyards)));
//		$form->add('image_title',	new cmfFormText(array('!empty', 'max'=>100)));
        $form->add('land', new cmfFormFile(array('path' => cmfPathShipyards)));
//        $form->add('land_title',	new cmfFormText(array('!empty', 'max'=>100)));
//		$form->add('title',			new cmfFormTextarea());
//		$form->add('keywords',		new cmfFormTextarea());
//		$form->add('description',	new cmfFormTextarea());
    }

    protected function saveStart(&$send) {
        parent::saveStart($send);
        if (!$this->getId()) {
            $send['date'] = date('Y-m-d H:i:s');
        }
        parent::saveStartUri($send, 'name', 25);
    }

}

?>