<?php

cmfAjax::start();
$r = cmfRegister::getRequest();

cmfLoad('board/cmfBoardEdit');
$boardEdit = new cmfBoardEdit();
$boardEdit->run();
?>