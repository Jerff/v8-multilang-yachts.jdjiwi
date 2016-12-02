<?php


cmfLoad('catalog/function');
$sql = cmfRegister::getSql();
list($header, $content, $notice, $title, $keywords, $description) = $sql->placeholder("SELECT header, content, notice, title, keywords, description FROM ?t WHERE id='shipyards/search' LIMIT 0,1", db_main)
                   ->fetchRow();

$this->assing('header', $header);
$this->assing('notice', $notice);
$this->assing('content', $content);
cmfMenu::setHeader($header);
cmfMenu::setRequest('sale');
cmfMenu::add($header, cmfGetUrl('/sale/new/search/'));

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $keywords);
cmfSeo::set('description', $description);

$_view = $sql->placeholder("SELECT param, view FROM ?t WHERE `group`='shipyards' AND visible='yes' ORDER BY pos", db_param_group_search)
                   ->fetchAssocAll('param');

$res = $sql->placeholder("SELECT id, name, prefix, `type`, value FROM ?t WHERE id ?@ AND visible='yes'", db_param, array_keys($_view))
                   ->fetchAssocAll();
foreach($res as $row) {    $id = $row['id'];
    $_view[$id]['name'] = $row['name'];
    $_view[$id]['prefix'] = $row['prefix'];
    $_view[$id]['type'] = $row['type'];
    if($_view[$id]['view']!='range') {        $value = cmfString::unserialize($row['value']);
        $list = $sql->placeholder("SELECT GROUP_CONCAT(value) FROM ?t WHERE `group`='shipyards' AND id IN(SELECT id FROM ?t WHERE page='/shipyards/yachts/' AND visible='yes') AND param=?", $row['type']==='checkbox' ? db_param_checkbox : db_param_select, db_search, $id)
                       ->fetchRow(0);
        $list = explode(',', $list);
        $list = array_combine($list, $list);
        foreach($value as $k=>$v) {            if(isset($list[$k])) {
                $value[$k] = $v .'&nbsp;'. $row['prefix'];
            } else {                unset($value[$k]);            }        }        $_view[$id]['value'] = $value;
    }
}
$this->assing('_view', $_view);


$this->assing2('resultUrl', cmfGetUrl('/sale/new/search/result/', array('form')));


?>
