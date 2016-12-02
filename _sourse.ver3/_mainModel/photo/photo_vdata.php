<?php

cmfDebug::destroy();
$offset = (int) cmfPages::getParam(1);
if (!$offset)
    exit;
if ($offset > 3000)
    exit;

if (!$_foto = cmfCache::get('photo-vdata' . $offset)) {

    $limit = cmfConfig::get('site', 'photoLimit');
    $sql = cmfRegister::getSql();
    $info = $sql->placeholder("SELECT id FROM ?t WHERE pages='photo' AND isVisible='yes'", db_menu_info)
            ->fetchAssoc();
    if (!$info)
        return 404;
    $list = $sql->placeholder("SELECT lang, name FROM ?t WHERE id=?", db_menu_info_lang, $info['id'])
            ->fetchAssocAll('lang');
    cmfLang::data($info, $list);

    $res = $sql->placeholder("SELECT id, image_main, image_small FROM ?t WHERE visible='yes' ORDER BY main, pos DESC LIMIT ?i, ?i", db_foto, $offset, $limit)
            ->fetchAssocAll('id');
    if (!$res)
        exit;
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_foto_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $_foto = array();
    $i = $offset + 1;
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $_foto[$id] = array(
            'title' => empty($row['name']) ? $info['name'] . ' ' . $i++ : htmlspecialchars($row['name']),
            'main' => cmfBaseImg . cmfPathFoto . $row['image_main'],
            'small' => cmfBaseImg . cmfPathFoto . $row['image_small']
        );
    }
    cmfCache::set('photo-vdata' . $offset, $_foto, 'photo');
}

$this->assing2('_foto', $_foto);
?>