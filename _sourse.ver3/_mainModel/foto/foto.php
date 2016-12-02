<?php


if((int)cmfPages::getParam(1)) {
    cmfRedirectSeo(cmfGetUrl('/foto/'));
}
$sql = cmfRegister::getSql();
list($header, $content, $title, $keywords, $description) = $sql->placeholder("SELECT header, content, title, keywords, description FROM ?t WHERE id='foto' LIMIT 0,1", db_main)
                   ->fetchRow();
$this->assing('header', $header);
$this->assing('content', $content);
cmfMenu::setHeader($header);
cmfMenu::add($header, cmfGetUrl('/foto/'));

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $keywords);
cmfSeo::set('description', $description);

$this->assing2('fotoUrl', cmfGetUrl('/foto/'));
$this->assing2('wallpapersUrl', cmfGetUrl('/wallpapers/'));


$limit = 16;
$res = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id, name, image_main, image_small FROM ?t WHERE visible='yes' ORDER BY main, pos DESC LIMIT 0, ?i", db_foto, $limit)
								->fetchAssocAll();
$_foto = array(); $i = 1;
foreach($res as $row) {
	$_foto[$row['id']] = array('title'=>empty($row['name']) ? $header .' '. $i++: htmlspecialchars($row['name']),
	                           'main'=>cmfBaseImg . cmfPathFoto . $row['image_main'],
	                           'small'=>cmfBaseImg . cmfPathFoto . $row['image_small']);
}
$this->assing2('_foto', $_foto);

$count = $sql->getFoundRows();
if(count($_foto)<$count) {
    $this->assing2('count', ceil($count/$limit));
    $this->assing('limit', $limit);
}

?>