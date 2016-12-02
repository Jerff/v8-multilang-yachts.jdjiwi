<?php

cmfLoad('ajax/cmfMainAjax');
cmfLoad('user/model/cmfUserModel');

class cmfUserRecoverPassword extends cmfMainAjax {

    function __construct() {
        $name = 'recoverPassword';
        $formUrl = cmfControllerUrl . '/user/recoverPassword/?';
        $func = 'cmfAjaxSendForm';

        parent::__construct($formUrl, $name, $func);
    }

    protected function init() {
        $form = $this->getForm();

        $form->add('email', new cmfFormTextMain(array('empty')));
        $form->add('login', new cmfFormTextMain(array('!empty', 'email', 'specialchars')));
        $form->add('captcha', new cmfFormKcaptchaMain());
    }

    public function run() {
        $data = $this->runStart();
        $this->getForm()->get('captcha')->free();
        $email = $data['login'];

        if ($row = cmfUserModel::getLogin($email)) {
            $data = array();
            $data['registerCommand'] = 'changePassword';
            $data['registerCod'] = $cod = sha1(rand(1, time()) . cmfSalt);

            if (cmfUserModel::save($data, $row['id'])) {
                $data['name'] = $row['name'];
                $data['login'] = $email;
                $data['userRecoverPasswordUrl'] = cmfGetUrl('/user/command/', array("recoverPassword/email/" . urlencode($email) . "/cod/$cod"));

                $cmfMail = new cmfMail();
                $cmfMail->sendTemplates('Личный кабинет: Запрос на восстановление пароля', $data, $email);

                $response = cmfAjax::get();
                $idForm = $this->getIdForm();
                $idHash = $this->getIdHash();
                $js = "
				$('#{$idForm}FormDiv').html('<p><b>" . word('Запрос на восстановление пароля отправлен') . "</b></p>');
				document.location.hash = '$idHash';";

                $response->script($js);
                die();
            } else {
                $this->runEnd(word('Запрос не отправлен'));
            }
        } else {
            cmfAjax::get()->script($this->getForm()->get('captcha')->jsReloadImage());
            $this->getForm()->setError('login', word('Пользователь не существует'));
            $this->runEnd(true);
        }
    }

    public function run1ok($email, $cod) {
        if (!$email or !$cod) {
            $this->runExit('error');
        }
        $user = new cmfUser();
        if ($row = cmfUserModel::changePassword($email, $cod)) {
            $cmfMail = new cmfMail();

            exit;
            $cmfMail->sendTemplates('Личный кабинет: Восстановление пароля', $row, $email);
            $this->runExit('ok');
        } else {
            $this->runExit('error');
        }
    }

    protected function runExit($command) {
        $url = cmfGetUrl('/user/command/', array("recoverPassword/action/{$command}"));
        cmfRedirect($url);
    }

}

?>