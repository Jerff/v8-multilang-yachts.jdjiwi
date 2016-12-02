<?php

cmfLoad('ajax/cmfMainAjax');
cmfLoad('user/model/cmfUserModel');
cmfLoad('user/cmfUserRegister');

class cmfBoardAdd extends cmfMainAjax {

    function __construct($name = 'boardAdd', $formUrl = null, $func = null) {
        $this->set('$isUser', cmfRegister::getUser()->is());
        $formUrl = cmfControllerUrl . '/board/add/?';
        $func = 'cmfAjaxSendForm';
        parent::__construct($formUrl, $name, $func, 3);
    }

    protected function init() {
        $form = $this->getForm(1);
        $form->add('email', new cmfFormTextMain(array('empty')));

        if (!$this->get('$isUser')) {
            $form->add('register', new cmfFormCheckbox(array('label' => word('Зарегистрироваться на сайте'))));
            $form->select('register', 'yes');
            $form->add('login', new cmfFormTextMain(array('name' => word('Электронная почта'), '!empty', 'email', 'min' => 6, 'max' => 100)));
        }

        $form = $this->getForm(2);
        if (!$this->get('$isUser')) {
            $form->add('password', new cmfFormPasswordMain(array('!empty', 'name' => word('Пароль'), 'confirmName' => 'userPasssword')));
            $form->add('password2', new cmfFormPasswordMain(array('!empty', 'confirmName' => 'userPasssword')));
        }

        $form = $this->getForm(3);
        $form->add('date', new cmfFormDateMain(null, null, array('name' => word('Дата показа'))));
        $form->add('dateEnd', new cmfFormDateMain());
        $form->select('dateEnd', date('Y-m-d H:i:s', time() + 2 * 30 * 24 * 60 * 60));

        $form->add('name', new cmfFormTextMain(array('max' => 255, 'name' => word('Заголовок'), '!empty')));
//        $form->add('phone', new cmfFormTextMain(array('max' => 255, 'name' => word('Номер телефона'), '!empty')));
        $form->add('contact', new cmfFormTextareaSmall(array('max' => 255, 'name' => word('Контакты'), '!empty')));
        $form->add('notice', new cmfFormTextareaMain(array('max' => 10000, 'name' => word('Текст объявления'), '!empty')));
        $form->add('price', new cmfFormTextRigthSmall(array('max' => 255, 'name' => word('Цена'), '!empty')));
        $form->add('currency', new cmfFormSelect(array('name' => word('Валюта'))));
        foreach (array('EURO', 'USD', 'RUR', 'UAH') as $value) {
            $form->addElement('currency', $value, $value);
        }

        $form->add('image', new cmfFormFileMain(array('path' => cmfPathBoard, 'fileSize' => 1, 'size' => array(1000, 1000))));

        $param = cmfRegister::getSql()->placeholder("SELECT p.id, p.value FROM ?t p LEFT JOIN ?t b ON(p.id=b.param) WHERE p.visible='yes' AND b.visible='yes' AND b.`group`='board' ORDER BY b.pos", db_param, db_param_group_notice)
                ->fetchAssocAll('id');
        $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_param_lang, array_keys($param))
                ->fetchAssocAll('id', 'lang');
        foreach ($param as $id => $row) {
            cmfLang::data($row, get($list, $id));
            $form->add('paramKey' . $id, new cmfFormSelectInt(array('name' => $row['name'])));
            foreach ($row['value'] = cmfString::unserialize($row['value']) as $key => $value) {
                $form->addElement('paramKey' . $id, '-1', word('Не выбрано'));
                $form->addElement('paramKey' . $id, $key, word($value));
            }
            $form->add('paramValue' . $id, new cmfFormTextSmall(array('max' => 255)));
            $param[$id] = $row;
        }
        $this->set('$param', $param);
    }

    protected function runStart() {
        $r = cmfRegister::getRequest();

        $isError = $isUpdate = $isReg = false;
        $data = array();
        foreach ($this->getFormAll() as $id => $form) {
            if ($id == 1
                    or ($id == 2 and !$this->get('$isUser') and $isReg)
                    or $id == 3) {
                $form->setRequest($r);
                $send = $form->handler();
                $isUpdate |= count($send);
                $data[] = $send;
                $isError |= $form->isError();

                if ($id == 1 and !$this->get('$isUser')) {
                    $isReg = $send['register'] === 'yes';
                }
            } else {
                $data[] = array();
            }
        }

        if (!$isError and $isUpdate) {
            return $this->getFormCount() > 1 ? $data : $data[0];
        }
        $this->runEnd(true);
    }

    public function run() {
        $isActivate = false;
//        cmfDebug::setError();

        list($userConfig, $userPassword, $boardValue) = $this->runStart();
        $board = $boardValue;

        if (!$this->get('$isUser')) {
            if ($userConfig['register'] === 'yes') {
                if (!cmfUserModel::isNew($userConfig['login'])) {
                    $this->getForm()->setError('login', word('Такой пользователь уже существует'));
                    $this->runEnd(true);
                }

                $userData = array(
                    'login' => $userConfig['login'],
                    'password' => $userPassword['password']
                );
                $userValue = array();
                $userRegister = new cmfUserRegister();
                if (!$userId = $userRegister->register($userData, $userValue, $isActivate)) {
                    $this->getForm()->setError('login', word('Пользователь не добавлен'));
                    $this->runEnd(true);
                }
                $boardValue['user'] = $userId;
                cmfRegister::getUser()->select($userData['login'], $userData['password']);
            }
            $boardValue['email'] = $userConfig['login'];
        } else {
            $boardValue['user'] = cmfRegister::getUser()->getId();
            $userConfig['login'] = $boardValue['email'] = cmfRegister::getUser()->email;
        }
        $email = $boardValue['email'];

        $boardValue['update'] = time();
        $boardValue['main'] = 'no';
        $boardValue['moder'] = 'no';
        $boardValue['visible'] = 'no';
        $boardValue['register'] = 'no';
        if (!empty($boardValue['image'])) {
            $send = $this->getForm(3)->processing(false, true);
            $boardValue['image'] = isset($send['image']) ? $send['image'] : '';
        }

        $param = array();
        foreach ($this->get('$param') as $k => $v) {
            if (get($board, 'paramKey' . $k) == -1) {
                $board['paramKey' . $k] = get($boardValue, 'paramValue' . $k);
            }
            foreach (array('paramKey' . $k, 'paramValue' . $k) as $key) {
                $param[$key] = get($boardValue, $key);
                unset($boardValue[$key]);
            }
        }
        $boardValue['param'] = cmfString::serialize($param);

        $boardId = cmfRegister::getSql()->add(db_board, $boardValue);

        cmfLoad('controller/cmfControllerBoard');
        cmfControllerBoard::updateUri($boardId);

        $board['date'] .= ' - ' . $boardValue['dateEnd'];

        $userMail = array();
        $userMail['boardUrl'] = cmfGetUrl('/board/item/', array($boardId));
        $userMail['editUrl'] = cmfProjectAdmin . 'board/edit/#&id=' . $boardId;
        $userMail['moderUrl'] = cmfProjectAdmin . 'board/edit/#&moder=1&id=' . $boardId;
        $userMail['name'] = $email;
        $userMail['data'] = cmfFormtaArray(array_merge(
                        $this->getForm(1)->processingName($userConfig), $this->getForm(3)->processingName($board)
                ));

        $cmfMail = new cmfMail();
        if (!empty($boardValue['image'])) {
            foreach (cmfString::unserialize($boardValue['image']) as $key => $value) {
                $cmfMail->addAttachment(cmfWWW . cmfPathBoard . $value, cmfWWW . cmfPathBoard . $value);
            }
        }
        $cmfMail->sendType('user', 'Доска объявлений: Новое объявление: Письмо менеджеру', $userMail);
        if (!empty($boardValue['image'])) {
            foreach (cmfString::unserialize($boardValue['image']) as $key => $value) {
                $cmfMail->addAttachment(cmfWWW . cmfPathBoard . $value, cmfWWW . cmfPathBoard . $value);
            }
        }
        $cmfMail->sendTemplates('Доска объявлений: Новое объявление: Письмо пользователю', $userMail, $email);
        cmfAjax::get()->redirect(cmfGetUrl('/board/item/', array($boardId)));
    }

}

?>