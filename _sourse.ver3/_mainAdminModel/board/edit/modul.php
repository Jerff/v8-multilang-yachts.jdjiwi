<?php

class board_edit_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('board_edit_db');

        // формы
        $form = $this->getForm();
        $form->add('date', new cmfFormTextDate());
        $form->add('dateEnd', new cmfFormTextDate());
        $form->add('main', new cmfFormCheckbox());
        $form->add('moder', new cmfFormCheckbox());
        $form->add('visible', new cmfFormCheckbox());

        $form->add('name', new cmfFormTextarea(array('max' => 255, '!empty')));
//        $form->add('phone', new cmfFormTextarea(array('max' => 255)));
        $form->add('contact', new cmfFormTextarea(array('max' => 255)));
        $form->add('notice', new cmfFormTextarea(array('max' => 10000)));
        $form->add('price', new cmfFormText(array('max' => 255)));
        $form->add('currency', new cmfFormSelect());

        foreach (cmfAdminSiteMenu::initCurrency() as $k => $v) {
            $form->addElement('currency', $k, $v);
        }

        $form->add('image', new cmfFormFile(array('path' => cmfPathBoard, 'size' => array(1000, 1000))));

        $param = cmfModulLoad('param_list_db')->getNameList(
                array(
            'id' => cmfModulLoad('param_group_notice_db')->getListId(
                    array('group' => 'board', 'AND', 'visible' => 'yes'), 'param'
            )
                ), array('value')
        );
        cmfGlobal::set('$param', $param);
        foreach ($param as $k => $v) {
            $form->add('paramKey' . $k, new cmfFormSelectInt());
            foreach (cmfString::unserialize($v['value']) as $key => $value) {
                $form->addElement('paramKey' . $k, '-1', 'Не выбрано');
                $form->addElement('paramKey' . $k, $key, $value);
            }
            $form->add('paramValue' . $k, new cmfFormText(array('max' => 255)));
        }
    }

    public function loadForm() {
		parent::loadForm();
        $form = $this->getForm();
        $form->select('dateEnd', date('Y-m-d H:i:s', time()+12*30*24*60*60));
        $form->select('moder', 'yes');
	}

    protected function saveStart(&$send) {
        parent::saveStart($send);
        $data = $this->getForm()->handler();

        $oldData = $this->getDb()->runData();
        $isModer = false;

        if(!$this->getId()) {
            $send['register'] === 'yes';
        }
        if (isset($send['moder'])) {
            $isModer = $send['moder'] === 'yes';
            if($isModer) {
                $send['register'] === 'yes';
            }
        } else {
            $isModer = $oldData['moder'] === 'yes';
        }

        $param = array();
        foreach (cmfGlobal::get('$param') as $k => $v) {
            foreach (array('paramKey' . $k, 'paramValue' . $k) as $key) {
                $param[$key] = get($data, $key);
                unset($send[$key]);
            }
        }


        if (key_exists('image', $send)) {
            if(!isset($send['isDelete'])) {
                $images = cmfString::unserialize($oldData['image']);
                $images[] = $send['image'];
                $send['image'] = cmfString::serialize($images);
            } else {
                unset($send['isDelete']);
            }
//            $this->setNewView();
            $data['image'] = $send['image'];
        } else {
            $data['image'] = $oldData['image'];
        }

        $send['param'] = $data['param'] = cmfString::serialize($param);
        if ($isModer) {
            $send['data'] = cmfString::serialize($data);
        }
        $send['update'] = time();
    }

    public function moder() {
        $send = array(
            'register' => 'yes',
            'moder' => 'yes',
            'visible' => 'yes',
            'data' => cmfString::serialize($this->getDb()->runData())
        );
        $this->getDb()->save($send);
    }

}

?>