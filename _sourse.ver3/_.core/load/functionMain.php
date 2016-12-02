<?php

//библиотека по загрузке модулей, файлов, бибилиотек
function cmfLoad($n) {
	if(!class_exists(basename($n))) {
		cmfLoadFile($n. '.php');
	}
}
function cmfLoadFile($file) {
	require_once($file);
}


// загрузка внешних библиотек
set_include_path(get_include_path() .
    PATH_SEPARATOR . cmfRoot .
    PATH_SEPARATOR . cmfWWW .
    PATH_SEPARATOR . cmfSourse .
    PATH_SEPARATOR . cmfSourse .'_.config/' .
    PATH_SEPARATOR . cmfSourse .'_.core/' .
    PATH_SEPARATOR . cmfSourse .'_.extension/' .
    PATH_SEPARATOR . cmfSourse .'_.plugin/' .
    PATH_SEPARATOR . cmfSourse .'_library/' .
    PATH_SEPARATOR . cmfSourse .'_library/PEAR/');

// загрузка модулей
function cmfLoadResponse() {
	cmfLoad('ajax/cmfAjaxResponse');
}
function cmfLoadAjax() {
	cmfLoad('ajax/cmfAjax');
}
function cmfLoadForm() {
	cmfLoad('form/cmfForm');
}
function cmfLoadRequest() {
	cmfLoad('request/cmfRequest');
}


?>