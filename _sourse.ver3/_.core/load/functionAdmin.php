<?php

//Загрузка составных админки
// загрузка модулей
cmfLoad('cache/cmfCacheAdmin');
function cmfModul($n) {
	if(!class_exists($n)) {
		if($n{0}==='_') {
			$n = '_'. str_replace('_', '/', substr($n, 1));
		} else {
            $n = str_replace('_', '/', $n);
		}
		cmfLoadFile(cmfAdminModel. $n .'.php');
	}
}

function &cmfModulLoad($n) {
	cmfModul($n);
	$n = new $n();
	return $n;
}


function __autoload($name) {
    if(strpos($name, 'driver_')===0 or strpos($name, '_interface_')===0) {
    	$type = preg_replace('~(driver_|_interface_)(interface|controller|modul|db).*~', '$2', $name);
    	switch($type) {
     		case 'interface':
    		case 'controller':
     		case 'modul':
     		case 'db':
    			cmfLoadFile(cmfAdminModel .'_.'. $type .'/'. $name .'.php');
    			return;
    	}
    }

    if(strpos($name, 'view_')===0) {
    	cmfLoadFile(cmfAdminModel .'_.view/'. $name .'.php');
    	return;
    }

    // автоматическая загрузка классов
    if(substr($name, 0, 3)!=='cmf') return;
    if(!class_exists('cmfAdminAutoload')) return;

    cmfAdminAutoload::autoload($name);
}

?>