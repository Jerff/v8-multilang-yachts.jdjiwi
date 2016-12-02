<?php

cmfLoad('ajax/cmfMainAjax');

class cmfContactForm extends cmfMainAjax {

    function __construct() {
        $name = 'OrderForm';
        $formUrl = cmfControllerUrl . '/form/contact/?';
        $func = 'cmfAjaxSendForm';

        parent::__construct($formUrl, $name, $func);
    }

    protected function init() {
        $form = $this->getForm();

        $form->add('name', new cmfFormTextMain(array('name' => word('Фамилия, имя, отчество'), '!empty', 'specialchars', 'max' => 200)));
        $form->add('email', new cmfFormTextMain(array('name' => word('Электронная почта'), '!empty', 'email', 'specialchars', 'max' => 200)));
        $form->add('phone', new cmfFormTextMain(array('name' => word('Контактный телефон'), 'specialchars', 'max' => 200)));
        $form->add('notice', new cmfFormTextareaMain(array('name' => word('Текст сообщения'), '!empty', 'specialchars', 'max' => 20000)));
        //$form->add('captcha',	new cmfFormKcaptcha());
        $form->add('login', new cmfFormText(array('empty')));
    }

    public function run() {
        $data = $this->runStart();
        //$this->getForm()->get('captcha')->free();

        $data2 = $this->getForm()->processingName($data);
        $email['data'] = cmfFormtaArray($data2);

        $cmfMail = new cmfMail();
        $cmfMail->sendType('form', 'Сообщение с контактов: Письмо менеджеру', $email);
        $response = cmfAjax::get();
        $idForm = $this->getIdForm();
        $idHash = $this->getIdHash();
        $js = "
        $('#{$idForm}FormDiv').html('<b>" . word('Сообщение отправлено') . "</b>');";

        $response->addScript($js);
    }

}

?>