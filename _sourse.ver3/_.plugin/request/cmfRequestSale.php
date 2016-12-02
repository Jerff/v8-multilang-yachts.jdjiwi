<?php


cmfLoad('ajax/cmfMainAjax');
class cmfRequestSale extends cmfMainAjax {

	function __construct() {
		$name = 'OrderForm';
		$formUrl = cmfControllerUrl .'/request/sale/?';
		$func = 'cmfAjaxSendForm';

		parent::__construct($formUrl, $name, $func, 2);
	}


	protected function init() {
        $form = $this->getForm(1);
		//$form->add('captcha',	new cmfFormKcaptcha());
		$form->add('login',	    new cmfFormText(array('empty')));

		$form->add('name', new cmfFormTextMain(array('name' => word('Имя'), '!empty', 'specialchars', 'max' => 200)));
        //$form->add('familyName',	new cmfFormText(array('name'=>'Фамилия', '!empty', 'specialchars', 'max'=>400)));
        //$form->add('patronymic',	new cmfFormText(array('name'=>'Отчество', '!empty', 'specialchars', 'max'=>400)));
		$form->add('email', new cmfFormTextMain(array('name' => word('Электронная почта'), '!empty', 'email', 'specialchars', 'max' => 200)));
        $form->add('phone', new cmfFormTextMain(array('name' => word('Контактный телефон'), '!empty', 'specialchars', 'max' => 200)));
        //$form->add('fax',	        new cmfFormText(array('name'=>'Факс', 'specialchars', 'max'=>200)));


        $form = $this->getForm(2);
        $form->add('yachts',	    new cmfFormTextareaMain(array('name'=>word('Яхта'), 'specialchars', 'max'=>40000)));
//        if(get($_GET, 'type')=='shipyards' or get($_POST, 'type')=='shipyards') {
//            $form->add('year',	        new cmfFormTextMain(array('name'=>word('Год выпуска'), 'specialchars', 'max'=>400)));
//        } else {
//            $form->add('year',	        new cmfFormTextMain(array('name'=>word('Год выпуска'),  'specialchars', 'max'=>400)));
//        }
//        $form->add('length',	    new cmfFormTextMain(array('name'=>word('Длина яхты'), 'specialchars', 'max'=>400,
//                                    'default'=>word('пример: 45 метров'))));

        /*$form->add('numberOfCabins',new cmfFormText(array('name'=>'Кол-во кают', '!empty', 'specialchars', 'max'=>400)));
        $form->add('typeSetting',	new cmfFormRadio(array('name'=>'Тип настройки', '!empty', 'specialchars', 'max'=>400)));
            $form->addElement('typeSetting', '1', 'FLYBRIDGE');
            $form->addElement('typeSetting', '2', 'OPEN');
            $form->addElement('typeSetting', '3', 'HARDTOP');
        $form->add('selectionOfYachts',	new cmfFormSelect(array('name'=>'Подбор яхты для', '!empty', 'specialchars', 'max'=>400)));
            $form->addElement('selectionOfYachts', '1', 'Семейного отдыха');
            $form->addElement('selectionOfYachts', '2', 'Корпоративного отдыха');
            $form->addElement('selectionOfYachts', '3', 'Прогулки "на одни день"');
            $form->select('selectionOfYachts', '2');*/
//        $form->add('budget',	    new cmfFormTextMain(array('name'=>word('Бюджет'), 'specialchars', 'max'=>400,
//                                    'default'=>word('пример: от 5 000 000 евро до 8 000 000 евро'))));
        $form->add('notice', new cmfFormTextareaMain(array('name' => word('Текст сообщения'), '!empty', 'specialchars', 'max' => 20000)));
	}




	public function run() {
		list($contact, $data) = $this->runStart();
		//$this->getForm()->get('captcha')->free();

        $email = array_merge($contact, $data);
        $email['contact'] = cmfFormtaArray($this->getForm(1)->processingName($contact));
        $email['data'] = cmfFormtaArray($this->getForm(2)->processingName($data));

        $cmfMail = new cmfMail();
        $cmfMail->sendType('form', 'Запрос на Покупку яхты: письмо менеджеру', $email);
        $cmfMail->sendTemplates('Запрос на Покупку яхты: письмо клиенту', $email, $contact['email']);

        $response = cmfAjax::get();
        $idForm = $this->getIdForm();
        $idHash = $this->getIdHash();
        $js = "
        $('#{$idForm}FormDiv').html('<br /><br /><h2>".  word('Сообщение отправлено'). "</h2>');";

        $response->addScript($js);
	}

}

?>