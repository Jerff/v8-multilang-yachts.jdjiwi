<?php


cmfDebug::destroy();
$offset = (int)cmfPages::getParam(1);
if(!$offset) exit;if($offset>3000) exit;

$limit = 16;
$sql = cmfRegister::getSql();
$header = $sql->placeholder("SELECT header FROM ?t WHERE id='foto' LIMIT 0,1", db_main)
                   ->fetchRow(0);

$res = $sql->placeholder("SELECT id, name, image_main, image_small FROM ?t WHERE visible='yes' ORDER BY main, pos DESC LIMIT ?i, ?i", db_foto, $offset, $limit)
								->fetchAssocAll();
$_foto = array(); $i = $offset+1;
foreach($res as $row) {
	$_foto[$row['id']] = array('title'=>empty($row['name']) ? $header .' '. $i++: htmlspecialchars($row['name']),
	                           'main'=>cmfBaseImg . cmfPathFoto . $row['image_main'],
	                           'small'=>cmfBaseImg . cmfPathFoto . $row['image_small']);
}
$this->assing2('_foto', $_foto);

?>
