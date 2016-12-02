<?php

cmfLoad('ajax/cmfMainAjax');

class cmfRequestArenda extends cmfMainAjax {

    function __construct() {
        $name = 'OrderForm';
        $formUrl = cmfControllerUrl . '/request/arenda/?';
        $func = 'cmfAjaxSendForm';

        parent::__construct($formUrl, $name, $func, 2);
    }

    protected function init() {
        $sql = cmfRegister::getSql();
        $form = $this->getForm(1);
        //$form->add('captcha',	    new cmfFormKcaptcha());
        $form->add('login', new cmfFormTextMain(array('empty')));

        $form->add('name', new cmfFormTextMain(array('name' => word('Имя'), '!empty', 'specialchars', 'max' => 200)));
        //$form->add('familyName',	new cmfFormText(array('name'=>'Фамилия', '!empty', 'specialchars', 'max'=>400)));
        //$form->add('patronymic',	new cmfFormText(array('name'=>'Отчество', '!empty', 'specialchars', 'max'=>400)));
        $form->add('email', new cmfFormTextMain(array('name' => word('Электронная почта'), '!empty', 'email', 'specialchars', 'max' => 200)));
        $form->add('phone', new cmfFormTextMain(array('name' => word('Контактный телефон'), '!empty', 'specialchars', 'max' => 200)));


        $form = $this->getForm(2);
        $form->add('yachts', new cmfFormTextareaMain(array('name' => word('Яхта'), 'specialchars', 'max' => 40000)));
//        $form->add('gost', new cmfFormTextMain(array('name' => word('Количество гостей'), 'specialchars', 'max' => 400)));
//        //$form->add('time',	        new cmfFormText(array('name'=>'Продолжительность аренды', 'specialchars', 'max'=>400)));
//
//        $form->add('charter', new cmfFormSelect(array('name' => word('Регион чартера'), 'specialchars', 'max' => 400)));
//        $res = $sql->placeholder("SELECT id FROM ?t WHERE parent='0' ORDER BY pos", db_arenda)
//                ->fetchAssocAll('id');
//        $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_arenda_lang, array_keys($res))
//                ->fetchAssocAll('id', 'lang');
//        foreach ($res as $id=>$row) {
//            cmfLang::data($row, get($list, $id));
//            $form->addElement('charter', $id, $row['name']);
//        }
//        //$form->addElement('charter', '-1', 'Международный чартер');
//        //$form->add('waterArea',	    new cmfFormTextarea(array('name'=>'Акватория', '!empty', 'specialchars', 'max'=>400)));
//        $form->add('typeYacht', new cmfFormSelect(array('name' => word('Тип яхты'), 'specialchars', 'max' => 400)));
//        $res = $sql->placeholder("SELECT id FROM ?t WHERE visible='yes' ORDER BY pos", db_arenda_type)
//                ->fetchAssocAll('id');
//        $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_arenda_type_lang, array_keys($res))
//                ->fetchAssocAll('id', 'lang');
//        foreach ($res as $id=>$row) {
//            cmfLang::data($row, get($list, $id));
//            $form->addElement('typeYacht', $row['id'], $row['name']);
//        }
//        $form->add('date1', new cmfFormTextSmall(array('name' => word('Сроки ареды'), 'specialchars', 'max' => 200)));
//        $form->add('date2', new cmfFormTextSmall(array('name' => '', 'specialchars', 'max' => 200)));
//        $form->add('length', new cmfFormTextMain(array('name' => word('Длина яхты'), 'specialchars', 'max' => 400,
//                    'default' => word('пример: 45 метров'))));



        /* $form->add('numberOfCabins',new cmfFormText(array('name'=>'Кол-во кают', 'specialchars', 'max'=>400)));
          $form->add('selectionOfYachts',	new cmfFormSelect(array('name'=>'Подбор яхты для', 'specialchars', 'max'=>400)));
          $form->addElement('selectionOfYachts', '1', 'Семейного отдыха');
          $form->addElement('selectionOfYachts', '2', 'Корпоративного отдыха');
          $form->addElement('selectionOfYachts', '3', 'Прогулки "на одни день"');
          $form->select('selectionOfYachts', '2');
          $form->add('budget',	    new cmfFormText(array('name'=>'Бюджет', 'specialchars', 'max'=>400))); */
        $form->add('notice', new cmfFormTextareaMain(array('name' => word('Текст сообщения'), '!empty', 'specialchars', 'max' => 20000)));
    }

    public function run() {
        list($contact, $data) = $this->runStart();
        //$this->getForm()->get('captcha')->free();
        if (!empty($data['date1']) or !empty($data['date2'])) {
            $v = '';
            if (!empty($data['date1'])) {
                $v .= 'c ' . $data['date1'] . ' ';
            }
            if (!empty($data['date2'])) {
                $v .= 'до ' . $data['date2'];
            }
            unset($data['date2']);
            $data['date1'] = $v;
        }
        $email = array_merge($contact, $data);
        $email['contact'] = cmfFormtaArray($this->getForm(1)->processingName($contact));
        $email['data'] = cmfFormtaArray($this->getForm(2)->processingName($data));

        $cmfMail = new cmfMail();
        $cmfMail->sendType('form', 'Запрос на Аренду яхты: письмо менеджеру', $email);
        if (!empty($contact['email'])) {
            $cmfMail->sendTemplates('Запрос на Аренду яхты: письмо клиенту', $email, $contact['email']);
        }


        $response = cmfAjax::get();
        $idForm = $this->getIdForm();
        $idHash = $this->getIdHash();
        $js = "
        $('#{$idForm}FormDiv').html('<br /><br /><h2>".  word('Сообщение отправлено'). "</h2>');";

        $response->addScript($js);
    }

}

?>