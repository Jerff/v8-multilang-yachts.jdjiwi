<?php

$sql = cmfRegister::getSql();
$res = $sql->placeholder("SELECT id, image_main FROM ?t WHERE visible='yes' ORDER BY main, pos ", db_selfie)
        ->fetchAssocAll('id');
$list = $sql->placeholder("SELECT id, name FROM ?t WHERE id ?@", db_selfie_lang, array_keys($res))
        ->fetchAssocAll('id', 'lang');
$_selfe = array();
$i = 0;
foreach ($res as $id => $row) {
    cmfLang::data($row, get($list, $id));
    $_selfe[$id] = array(
        'title' => empty($row['name']) ? $i++ : htmlspecialchars($row['name']),
        'main' => cmfBaseImg . cmfPathSelfie . $row['image_main']
    );
}
$this->assing2('_selfe', $_selfe);

?>