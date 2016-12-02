<?php


// преобразованны mix данные в строки javascript-а
function cmfToJsString($d) {
	if(is_string($d)) return str_replace(array("\n","\r"), array('\n','\r'), addslashes($d));
	if(is_array($d)) {
		reset($d);
		while(list($k, $v) = each($d))
			$d[$k] = cmfToJsString($v);
		return $d;
	}
	return cmfToJsString((string)$d);
}

?>