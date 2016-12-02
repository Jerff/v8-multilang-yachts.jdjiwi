<?php

if ($mBrand = cmfCache::get('brand')) {

} else {

    $sql = cmfRegister::getSql();
    $res = $sql->placeholder("SELECT id, image FROM ?t WHERE visible='yes' ORDER BY pos", db_brand)
            ->fetchAssocAll('id');
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_brand_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mBrand = array();
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mBrand[$id] = array(
            'title' => htmlspecialchars($row['name']),
            'image' => cmfBaseImg . cmfPathBrand . $row['image']
        );
        list(, , , $mBrand[$id]['width']) = getimagesize(cmfWWW . cmfPathBrand . $row['image']);
    }
    cmfCache::set('brand', $mBrand, 'photo');
}
$this->assing('mBrand', $mBrand);
?>