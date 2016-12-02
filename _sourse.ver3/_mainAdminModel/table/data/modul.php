<?php


class table_data_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('table_data_db');

		// формы
		$form = $this->getForm();
		$this->setNewPos();
		$form->add('visible',		new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->getForm();
        foreach (cmfGlobal::get('filedList') as $index => $value) {
            switch ($value) {
                case 'int':
                    $form->add('field' . $index,		new cmfFormTextInt());
                    break;

                case 'int':
                    $form->add('field' . $index,		new cmfFormTextFloat());
                    break;

                case 'text':
                    $form->add('field' . $index,		new cmfFormTextarea());
                    break;

                case 'string':
                default:
                    $form->add('field' . $index,		new cmfFormText());
                    break;
            }
        }
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
        if ($this->getId()) {
            $data = cmfModulLoad('table_data_db')->getDataId($this->getId());
            $field = cmfString::unserialize(get($data, 'value'));
        } else {
            $field = array();
            $send['table'] = $this->getFilter('table');
        }

        $isChange = false;
        foreach (cmfGlobal::get('filedList') as $index => $value) {
            if(isset($send['field' .$index])) {
                $isChange = true;
                $field[$index] = $send['field' .$index];
                unset($send['field' .$index]);
            }
        }
        if($isChange) {
            $send['value'] = cmfString::serialize($field);
        }
	}

}

?>