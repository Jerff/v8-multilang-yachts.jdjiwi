<?php

class arenda_yachts_foto_multi_modul extends driver_modul_list {

    protected function init() {
        parent::init();

        $this->setDb('arenda_yachts_foto_multi_db');

        // формы
        $form = $this->getForm();
        $this->setNewPos();

        $form->add('name', new cmfFormText(array('max' => 255, '1!empty')));
        $form->add('main', new cmfFormCheckbox());
        $form->add('visible', new cmfFormCheckbox());

        $size = array();
        $size['main'] = array(yachtsFotoWidth, yachtsFotoHeight);
        $size['small'] = array(yachtsFotoSmallWidth, yachtsFotoSmallHeight);
        $form->add('image', new cmfFormImage(array('name' => 'image', 'path' => cmfPathArendaYachtsFoto, 'size' => $size, 'addField')));
    }

    protected function saveStart(&$send) {
        if (empty($send['name']) and empty($send['image'])) {
            $send = array();
            return;
        }

        if (count($send) and !$this->getId()) {
            $send['yachts'] = cmfAdminMenu::getSubMenuId();
        }
        if(!empty($send)) { $send['time'] = time(); }
		cmfCommand::set('isMultiUplod');
        parent::saveStart($send);
    }

}

?>