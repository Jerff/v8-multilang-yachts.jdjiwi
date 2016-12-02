<?php

$command = word('Неправильная команда');
$name = word('Личный кабинет');


cmfUrlParam::start(cmfPages::getParam(1));
switch (cmfUrlParam::get('action')) {
    case 'recoverPassword':

        $name = word('Восстановление пароля');
        switch (cmfUrlParam::get('command')) {
            case 1:
                $command = word('Пароль сменен');
                break;

            case 2:
                $command = word('Неправильная почта или код подверждения');
                break;
        }
        break;

    case 'userRegister':

        $name = word('Регистрация пользователя');
        switch (cmfUrlParam::get('command')) {
            case 1:
                $command = word('Неправильная код подверждения');
                break;

            case 2:
                $command = word('Пользователь уже зарегистрирован');
                break;

            case 3:
                $command = word('Регистрация завершена');
                break;
        }
        break;
}

cmfSeo::set('command', $name);

$this->assing('name', $name);
$this->assing('content', $command);
?>
