<?php

cmfLoad('ajax/cmfMainAjax');

class cmfUserEnter extends cmfMainAjax {

    function __construct($name = '') {
        if (empty($name)) {
            $name = cmfRegister::getRequest()->getGet('type');
        }
        if (empty($name)) {
            $name = 'enter';
        }
        $this->set('type', $name);

        $formUrl = cmfControllerUrl . '/user/enter/?type=' . $name;
        $func = 'cmfAjaxSendForm';

//        preg_match('~([a-z\-]+)\-([0-9]+)~', $this->get('type'), $tmp);
//        $type = empty($tmp) ? $this->get('type') : $tmp[1];
        parent::__construct($formUrl, $name, $func);
    }

    protected function init() {
        $form = $this->getForm();

        $form->add('email', new cmfFormTextMain(array('empty')));
        $form->add('login', new cmfFormTextMain(array('!empty', 'email', 'specialchars')));
        $form->add('password', new cmfFormPasswordMain(array('!empty', 'default')));
    }

    public function run() {
        $data = $this->runStart();
        unset($data['email']);

        $is = cmfRegister::getUser()->select($data['login'], $data['password']);
        if ($is) {
            $url = cmfRegister::getRequest()->getPost('url');
            $index = cmfGetUrl('/index/');
            if (strpos($url, $index) === 0) {

            } else {
                preg_match('~([a-z\-]+)\-([0-9]+)~', $this->get('type'), $tmp);
                switch (empty($tmp) ? $this->get('type') : $tmp[1]) {
                    case 'board-add':
                        $url = cmfGetUrl('/board/add/');
                        break;

                    case 'board-edit':
                        $url = empty($tmp[2]) ? cmfGetUrl('/board/add/') : cmfGetUrl('/board/edit/', array($tmp[2]));
                        break;

                    default:
                        $url = cmfGetUrl('/user/');
                        break;
                }
            }
            cmfAjax::get()->redirect($url);
        } else {
            $this->getForm()->setError('login', word('Неверен логин или пароль'));
            $this->runEnd(true);
        }
    }

}

?>