<?php

cmfAjax::start();
$r = cmfRegister::getRequest();

cmfLoad('board/cmfBoardAdd');
$boardAdd = new cmfBoardAdd();
$boardAdd->run();
?>