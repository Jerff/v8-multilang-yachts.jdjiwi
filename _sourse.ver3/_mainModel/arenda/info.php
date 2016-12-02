<?php

if(cmfPages::isMain('/arenda/info2/')) {
    cmfRedirectSeo(cmfGetUrl('/arenda/info/'));
}

$sql = cmfRegister::getSql();
list($header, $content, $title, $keywords, $description) = $sql->placeholder("SELECT header, content, title, keywords, description FROM ?t WHERE id='arenda/info' LIMIT 0,1", db_main)
                   ->fetchRow();
$this->assing('header', $header);
$this->assing('content', $content);
cmfMenu::setHeader($header);
cmfMenu::setRequest('arenda');
cmfGlobal::set('isUkraine', true);
cmfMenu::add($header, cmfGetUrl('/arenda/info/'));

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $keywords);
cmfSeo::set('description', $description);


//$res = $sql->placeholder("SELECT image, name, link FROM ?t WHERE page='arenda/info' AND parent='arenda/info' AND visible='yes' ORDER BY main, pos DESC", db_main_slider)
//                        ->fetchAssocAll();
//$_slider = array();
//foreach($res as $row) {
//    $_slider[] = array('title'=>htmlspecialchars($row['name']),
//                       'link'=>$row['link'],
//                       'image'=>cmfBaseImg . cmfPathMain . $row['image']);
//}
//if($_slider) {
//    $this->assing('_slider', $_slider);
//    $this->assing2('isSlider', count($_slider)>1);
//}

?>