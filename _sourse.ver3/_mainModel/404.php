<?php

//$this->setTeplates('main.404.php');
header("HTTP/1.0 404 Not Found");
$content = cmfRegister::getSql()->placeholder("SELECT content FROM ?t WHERE id IN(SELECT id FROM ?t WHERE name='Системная страница: 404 error') AND lang=?", db_content_static_lang, db_content_static, cmfLang::getId())
        ->fetchRow(0);

define('IS_404_STATUS', true);
cmfGlobal::set('body', 'info');
$this->assing('content', $content);
cmfMenu::add('404 Not Found');
?>