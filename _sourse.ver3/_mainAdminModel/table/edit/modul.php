<?php


class table_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('table_edit_db');

		// формы
		$form = $this->getForm();
        $form->add('name',		new cmfFormTextarea(array('max'=>255, '!empty')));
        $form->add('colum',		new cmfFormTextInt());
		$form->add('visible',	new cmfFormCheckbox());
	}

    public function loadForm() {
		parent::loadForm();
        $form = $this->getForm();

        if($this->getId()) {
            $data = $this->getData();
            if(!empty($data['colum'])) {
                cmfGlobal::set('fieldColum', (int)$data['colum']);
                for ($index = 1; $index <= (int)$data['colum']; $index++) {
                    $form->add('field'. $index,		new cmfFormSelect());
                    $form->addElement('field'. $index, 'string', 'Строка');
//                    $form->addElement('field'. $index, 'link', 'Ссылка');
//                    $form->addElement('field'. $index, 'int', 'Число');
//                    $form->addElement('field'. $index, 'float', 'Дробное число');
                    $form->addElement('field'. $index, 'text', 'Текст');
                }
            }
        }
	}

	protected function selectForm(&$data) {
        parent::selectForm($data);
        $filed = cmfString::unserialize(get($data, 'field'));
        $form = $this->getForm();
        for ($index = 1; $index <= cmfGlobal::get('fieldColum'); $index++) {
            $form->select('field'. $index, get($filed, $index));
        }
	}


	protected function saveStart(&$send) {
		parent::saveStart($send);
        if(isset($send['colum'])) {
			$this->setNewView();
        }

        $filed = array();
        for ($index = 1; $index <= cmfGlobal::get('fieldColum'); $index++) {
            $filed[$index] = get($send, 'field'. $index);
            unset($send['field'. $index]);
        }
        $send['field'] = cmfString::serialize($filed);
	}


}

?>