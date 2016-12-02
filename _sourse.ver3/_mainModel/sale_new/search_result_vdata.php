<?php


cmfDebug::destroy();
cmfLoad('catalog/function');
$searchUri = cmfPages::getParam(1);
$offset = (int)cmfPages::getParam(2);
if(!$offset) exit;if($offset>3000) exit;

$limit = 18;
$sql = cmfRegister::getSql();
$_view = $sql->placeholder("SELECT param, view FROM ?t WHERE `group`='shipyards' AND visible='yes' ORDER BY pos", db_param_group_search)
                   ->fetchAssocAll('param');
$res = $sql->placeholder("SELECT id, name, prefix, `type`, value FROM ?t WHERE id ?@ AND visible='yes'", db_param, array_keys($_view))
                   ->fetchAssocAll();
foreach($res as $row) {
    $id = $row['id'];
    $_view[$id]['name'] = $row['name'];
    $_view[$id]['prefix'] = $row['prefix'];
    $_view[$id]['type'] = $row['type'];
    $value = cmfString::unserialize($row['value']);
    if($row['prefix'] and $_view[$id]['view']!='range') {
        foreach($value as $k=>$v) {
            $value[$k] = $v .'&nbsp;'. $row['prefix'];
        }
    }
    $_view[$id]['value'] = $value;
}


$list = explode('/', $searchUri);
$where = $param = array();
foreach($list as $v) {
    $v = explode(':', $v);
    if(isset($v[1])) {
        $param[$v[0]] = array_map('cmfToFloat', explode('-', $v[1]));
    }
}


$i = 0;
foreach($_view as $k=>$v) {
    if(isset($param[$k]))
    switch($v['view']) {
        case 'range':
            $min = get2($param, $k, 0);
            $max = get2($param, $k, 1);
            if($v['type']=='text') {
                $where[] = $sql->getQuery("param{$i}>=? AND param{$i}<=? AND ", $min, $max);
            } else {
                $list = array();
                foreach($v['value'] as $k2=>$v2) {
                    $v2 = cmfToFloat($v2);
                    if(!$min) {
                        if($v2<=$max) {
                            $list[$k2] = $k2;
                        }
                    } elseif(!$max) {
                        if($min<=$v2) {
                            $list[$k2] = $k2;
                        }
                    } elseif($min<=$v2 and $v2<=$max) {
                        $list[$k2] = $k2;
                    }
                }
                if($list) {
                    $where[] = $sql->getQuery("param{$i} ?@ AND ", $list);
                }
            }
            break;

        case 'checkbox':
            $where[] = $sql->getQuery("param{$i} ?@ AND ", $param[$k]);
            break;

        case 'radio':
            if($param[$k][0]>=0)
            $where[] = $sql->getQuery("param{$i}=? AND ", $param[$k][0]);
            break;
    }
    $i++;
}


$list = $sql->placeholder("SELECT id FROM ?t WHERE ?w page='/shipyards/yachts/' AND visible='yes' GROUP BY id ORDER BY `name` LIMIT ?i, ?i", db_search, $where, $offset, $limit)
                    ->fetchRowAll(0, 0);
if(!$list) exit;
$paramId = cmfConfig::get('site', 'shipyardsParam');
$paramValue = cmfParam::getParamId($paramId);
$res = $sql->placeholder("SELECT y.id, y.uri, y.name, y.image_small, y.image_title, y.param, y.price, y.currency, s.name AS sName, s.uri AS sUri FROM ?t y LEFT JOIN ?t s ON(s.id=y.shipyards) WHERE s.id=y.shipyards AND y.id ?@ AND y.visible='yes' ORDER BY FIELD(y.`id`, ?s)", db_shipyards_yachts, db_shipyards, $list, implode(',', $list))
								->fetchAssocAll();
$_yachts = array();
foreach($res as $row) {
    $_yachts[$row['id']] = array('shipyards'=>$row['sName'],
                                 'name'=>$row['name'],
                                 'param'=>cmfParam::selectParam($row['param'], $paramId, $paramValue),
                                 'image'=>cmfBaseImg . cmfPathShipyardsYachts . $row['image_small'],
                        		 'title'=>htmlspecialchars($row['image_title']),
                        		 'price'=>cmfPrice::view($row['price'], $row['currency']),
                        		 'url'=>cmfGetUrl('/shipyards/yachts/', array($row['sUri'], $row['uri'])));
}
$this->assing2('_yachts', array_chunk($_yachts, 3));
list($phone1, $phone2) = explode(": ", cmfConfig::get('site', 'shipyardsPhone'));
$this->assing2('phone1', $phone1);
$this->assing2('phone2', $phone2);

?>
