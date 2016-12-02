<?php

if ($map = cmfCache::get('map')) {

} else {


    $sql = cmfRegister::getSql();
    $map = array();
    $id = 'info';
    $map[0][$id] = array('name' => word('Полезное'));
    $res = cmfRegister::getSql()->placeholder("SELECT id, isUri as url FROM ?t WHERE isVisible='yes'", db_menu_info)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_menu_info_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    foreach ($res as $k => $row) {
        cmfLang::data($row, get($list, $k));
        $map[$id][] = array(
            'name' => $row['name'],
            'url' => cmfGetUrl('/info/', array($row['url']))
        );
    }
    $res = cmfRegister::getSql()->placeholder("SELECT id, name, isUri as url FROM ?t WHERE isVisible='yes'", db_content)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_content_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    foreach ($res as $k => $row) {
        cmfLang::data($row, get($list, $k));
        $map[$id][] = array(
            'name' => $row['name'],
            'url' => cmfGetUrl('/info/', array($row['url']))
        );
    }


    $id = 'shipyards';
    infoMap($map, $id);
    $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY `main`, pos", db_shipyards)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_shipyards_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $i = 0;
    foreach ($res as $k => $row) {
        cmfLang::data($row, get($list, $k));
        $map[$id][] = array(
            'name' => $row['name'],
            'url' => cmfGetUrl('/shipyards/one/', array($row['uri']))
        );
        $res2 = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' AND shipyards='" . $row['id'] . "'", db_shipyards_yachts)
                ->fetchAssocAll('id');
        $list2 = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_shipyards_yachts_lang, array_keys($res2))
                ->fetchAssocAll('id', 'lang');
        $map[$id][$i]['third'] = array();
        foreach ($res2 as $k => $row2) {
            cmfLang::data($row2, get($list2, $k));
            $map[$id][$i]['third'][] = array(
                'name' => $row2['name'],
                'url' => cmfGetUrl('/shipyards/one/', array($row['uri'] . '/' . $row2['uri']))
            );
        }
        $i++;
    }


    $id = 'new_sale';
    infoMap($map, $id);
    $map[$id][] = array(
        'name' => word('Подбор яхты'),
        'url' => cmfGetUrl('/sale/new/search/')
    );
    $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_shipyards_type)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_shipyards_type_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    foreach ($res as $k => $row) {
        cmfLang::data($row, get($list, $k));
        $map[$id][] = array(
            'name' => $row['name'],
            'url' => cmfGetUrl('/sale/new/type/', array($row['uri']))
        );
    }


    $id = 'brokerage';
    infoMap($map, $id);
    $res = $sql->placeholder("SELECT id, uri FROM ?t WHERE visible='yes' ORDER BY pos", db_brokerage_type)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_brokerage_type_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    foreach ($res as $k => $row) {
        cmfLang::data($row, get($list, $k));
        $map[$id][] = array(
            'name' => $row['name'],
            'url' => cmfGetUrl('/brokerage/type/', array($row['uri']))
        );
    }
    $map[$id][] = null;
    $res = $sql->placeholder("SELECT y.id, t.uri AS tUri, .y.uri FROM ?t y LEFT JOIN ?t t ON(y.type=t.id) WHERE t.visible='yes' AND y.visible='yes' ORDER BY y.name", db_brokerage_yachts, db_brokerage_type)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_brokerage_yachts_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    foreach ($res as $k => $row) {
        cmfLang::data($row, get($list, $k));
        $map[$id][] = array(
            'name' => $row['name'],
            'url' => cmfGetUrl('/brokerage/yachts/', array($row['tUri'], $row['uri']))
        );
    }



    $id = 'arenda';
    infoMap($map, $id);
    $res = $sql->placeholder("SELECT y.id, t.uri AS tUri, .y.uri FROM ?t y LEFT JOIN ?t t ON(y.type=t.id) WHERE t.visible='yes' AND y.visible='yes' ORDER BY y.name", db_arenda_yachts, db_arenda_type)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_arenda_yachts_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    foreach ($res as $k => $row) {
        cmfLang::data($row, get($list, $k));
        $map[$id][] = array('name' => $row['name'],
            'url' => cmfGetUrl('/arenda/yachts/', array($row['tUri'], $row['uri'])));
    }


    $parentId = 3;
    $id = 'arenda-ukraine';
    infoMap($map, $id);
    $res = $sql->placeholder("SELECT id, parent, name, isUri FROM ?t WHERE id=? OR parent=? OR path LIKE '%[?i]%' AND isVisible='yes' AND level<'4' ORDER BY pos", db_arenda, $parentId, $parentId, $parentId)
            ->fetchAssocAll('parent', 'id');
    $res2 = $sql->placeholder("SELECT id FROM ?t WHERE id=? OR parent=? OR path LIKE '%[?i]%' AND isVisible='yes' AND level<'4' ORDER BY pos", db_arenda, $parentId, $parentId, $parentId)
            ->fetchRowAll(0, 0);
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_arenda_lang, array_keys($res2))
            ->fetchAssocAll('id', 'lang');
    reformMapTree($map[$id], $res, $list, $parentId);


    $id = 'charter';
    infoMap($map, $id);
    $res = $sql->placeholder("SELECT id, parent, isUri FROM ?t WHERE id!=? AND isVisible='yes' AND level<'4' ORDER BY pos", db_arenda, $parentId)
            ->fetchAssocAll('parent', 'id');
    $res2 = $sql->placeholder("SELECT id FROM ?t WHERE id!=? AND isVisible='yes' AND level<'4' ORDER BY pos", db_arenda, $parentId)
            ->fetchRowAll(0, 0);
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_arenda_lang, array_keys($res2))
            ->fetchAssocAll('id', 'lang');
    foreach ($res[0] as $k => $row) {
        cmfLang::data($row, get($list, $k));
        $map[$id][] = array(
            'name' => $row['name'],
            'url' => cmfGetUrl('/arenda/', array($row['isUri']))
        );
        reformMapTree($map[$id], $res, $list, $k);
        $map[$id][] = null;
    }


    cmfCache::set('map', $map, 'map');
}

function infoMap(&$map, $id) {
    $sql = cmfRegister::getSql();
    $info = $sql->placeholder("SELECT id FROM ?t WHERE pages=? AND isVisible='yes'", db_menu_info, $id)
            ->fetchAssoc();
    $list = $sql->placeholder("SELECT lang, name FROM ?t WHERE id=?", db_menu_info_lang, $info['id'])
            ->fetchAssocAll('lang');
    cmfLang::data($info, $list);
    $map[0][$id] = array(
        'name' => $info['name']
    );
}

function reformMapTree(&$map, $res, $list, $parent) {
    if (!isset($res[$parent]))
        return;
    foreach ($res[$parent] as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $map[] = array(
            'name' => $row['name'],
            'url' => cmfGetUrl('/arenda/', array($row['isUri']))
        );
        reformMapTree($map, $res, $list, $id);
    }
}

$this->assing('map', $map);
?>