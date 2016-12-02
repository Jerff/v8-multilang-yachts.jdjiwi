<?php

cmfLoad('ajax/cmfMainAjax');
cmfLoad('user/model/cmfUserModel');

class cmfUserRegister extends cmfMainAjax {

    function __construct($name = null, $formUrl = null, $func = null) {
        if (empty($name)) {
            $name = cmfRegister::getRequest()->getGet('name');
        }
        if (empty($name)) {
            $name = 'register';
        }
        $this->set('type', $name);
        $formUrl = cmfControllerUrl . '/user/register/?name=' . $name;
        $func = 'cmfAjaxSendForm';

        parent::__construct($formUrl, $name, $func, 2);
    }

    protected function init() {
        $form = $this->getForm(1);
        $form->add('email', new cmfFormTextMain(array('empty')));
        $form->add('login', new cmfFormTextMain(array('name' => word('Электронная почта'), '!empty', 'email', 'min' => 6, 'max' => 100)));
        $form->add('password', new cmfFormPasswordMain(array('!empty', 'name' => word('Пароль'), 'confirmName' => 'userPasssword')));
        $form->add('password2', new cmfFormPasswordMain(array('!empty', 'confirmName' => 'userPasssword')));
        //$form->add('captcha',		    new cmfFormKcaptcha());


        $form = $this->getForm(2);
    }

    public function run1() {
        $isActivate = false;
        cmfConfig::get('user', 'isActivate');

        list($userData, $userValue) = $this->runStart();
        //$this->getForm()->get('captcha')->free();

        $response = cmfAjax::get();
        if (!cmfUserModel::isNew($userData['login'])) {
            $this->getForm()->setError('login', word('Такой пользователь уже существует'));
            $this->runEnd(true);
        }

        if (!$userId = $this->register($userData, $userValue, $isActivate)) {
            $this->getForm()->setError('login', word('Пользователь не добавлен'));
            $this->runEnd(true);
        }

        if ($isActivate) {
            $content = cmfContent::getStatic('Личный кабинет: Регистрация (с активацией)');
        } else {
            cmfRegister::getUser()->select($userData['login'], $userData['password']);
            switch ($this->get('type')) {
                case 'board-add':
                    $url = cmfGetUrl('/board/add/');
                    break;

                default:
                    $url = cmfGetUrl('/user/');
                    break;
            }

            $response->redirect($url);
        }

        $idHash = $this->getIdHash();
        $response->hash("userRegisterHash")
                ->html($this->getIdForm(), $content);
    }

    public function register($user, $userData, $isActivate = false) {
        $login = $user['login'];
        $password = $user['password'];
        $user['email'] = $email = $user['login'];

        $userMail = array();
        $userMail['login'] = $user['login'];
        $userMail['password'] = $user['password'];
        $userMail['name'] = cmfUser::generateName($user);

        if ($isActivate) {
            $user['registerCommand'] = 'register';
            $user['registerCod'] = $cod = sha1(rand(1, time()) . cmfSalt);

            $user['register'] = 'no';
            $user['visible'] = 'no';
        } else {

            $user['register'] = 'yes';
            $user['visible'] = 'yes';
            $user['registerCommand'] = '';
        }

        $id = cmfUserModel::save($user);
        if (!$id)
            return false;

        if ($userData) {
            cmfUserModel::saveParam($userData, $id);
        }

        $userMail['data'] = cmfFormtaArray(array_merge($this->getForm(1)->processingName($user), $this->getForm(2)->processingName($userData)));
        $userMail['adminUrl'] = cmfProjectAdmin . '#/user/edit/&id=' . $id;


        if ($isActivate) {
            $userMail['activateUrl'] = cmfGetUrl('/user/command/', array('userRegister/user/' . $id . '/cod/' . $cod));

            $cmfMail = new cmfMail();
            $cmfMail->sendType('user', 'Личный кабинет: Регистрация: Письмо админу (с активацией)', $userMail);
            $cmfMail->sendTemplates('Личный кабинет: Регистрация (c активацией)', $userMail, $email);
        } else {
            $cmfMail = new cmfMail();
            $cmfMail->sendType('user', 'Личный кабинет: Регистрация: Письмо админу (без активации)', $userMail);
            $cmfMail->sendTemplates('Личный кабинет: Регистрация (без активации)', $userMail, $email);
        }
        return $id;
    }

    public static function userActivate($id, $cod) {
        if (!$id or !$cod) {
            self::runExit('error');
        }

        $row = cmfRegister::getSql()->placeholder("SELECT email, name, login, registerCommand, register FROM ?t WHERE id=? AND registerCod=?", db_user, $id, $cod)
                ->fetchAssoc();
        if (!$row) {
            self::runExit('error');
        }
        if ($row['registerCommand'] !== 'register' or $row['register'] === 'yes') {
            self::runExit('error');
        }
        cmfRegister::getSql()->add(db_user, array('registerCommand' => '', 'register' => 'yes', 'visible' => 'yes'), $id);

        $cmfMail = new cmfMail();
        $cmfMail->sendTemplates('Личный кабинет: Активация', $row, $row['email']);
        self::runExit('ok');
    }

    protected static function runExit($command) {
        $url = cmfGetUrl('/user/command/', array('userRegister/action/' . $command));
        cmfRedirect($url);
    }

}

?>