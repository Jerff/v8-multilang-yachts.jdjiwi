<?php


function cmfCrc32($n) {	return crc32($n) & 0xffffffff;}

function cmfToFloat($d) {
	$d = preg_replace('#\s#mS', '', $d);
	$d = str_replace(	array(	',', '-'),
								'.',
								$d);
	return str_replace(',', '.', (float)$d);
}

function cmfToArrayInt($d) {
	if(is_array($d)) {
		foreach($d as &$v) cmfToArrayInt($v);
	} else {
		$d = (int)$d;
	}
}

?>