<?php

$pageId = cmfGlobal::get('$pageId');
$limit = cmfConfig::get('site', 'wallpapersLimit');
$offset = ($pageId - 1) * $limit;
if ($offset > 3000)
    return 404;

if ($mWallpapers = cmfCache::getParam('wallpapers', $pageId)) {
    list($mWallpapers, $mFoto, $mWallpapersPageUrl, $mFotoPageUrl) = $mWallpapers;
} else {

    $sql = cmfRegister::getSql();
    $res = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id, image_main, image_small FROM ?t WHERE visible='yes' ORDER BY main, pos DESC LIMIT ?i, ?i", db_wallpapers, $offset, $limit)
            ->fetchAssocAll('id');
//    if (empty($res))
//        return 404;
    $count = $sql->getFoundRows();
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_wallpapers_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mWallpapers = array();
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $title = empty($row['name']) ? cmfGlobal::get('$infoName') . ' wallpapers ' . $row['id'] : htmlspecialchars($row['name']);
        $mWallpapers[$id] = array(
            'title' => $title,
            'main' => cmfBaseImg . cmfPathWallpapers . $row['image_main'],
            'small' => cmfImage::preview(cmfBaseImg . cmfPathWallpapers . $row['image_main'], 170, 128, $title, $row['id'])
        );
        list(,,, $mWallpapers[$id]['width']) = getimagesize($mWallpapers[$id]['small']);
    }
    $max = ceil($count/cmfConfig::get('site', 'wallpapersPages'));
    $mWallpapersPageUrl = cmfPagination::generate($pageId > $max ? $max : $pageId, $count, $limit, cmfConfig::get('site', 'wallpapersPages'), create_function('&$page, $k, $v', '
            $page[$k]["url"] = $k==1 ? cmfGetUrl("/wallpapers/") : cmfGetUrl("/wallpapers/page/", array($k));'));

    $res = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id, image_main, image_small FROM ?t WHERE visible='yes' ORDER BY main, pos DESC LIMIT ?i, ?i", db_foto, $offset, $limit)
            ->fetchAssocAll('id');
//    if (empty($res))
//        return 404;
    $count = $sql->getFoundRows();
    $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_foto_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mFoto = array();
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $title = empty($row['name']) ? cmfGlobal::get('$infoName') . ' foto ' . $row['id'] : htmlspecialchars($row['name']);
        $mFoto[$id] = array(
            'title' => $title,
            'main' => cmfBaseImg . cmfPathFoto . $row['image_main'],
            'small' => cmfImage::preview(cmfBaseImg . cmfPathFoto . $row['image_main'], 170, 97, $title, $row['id'])
        );
        list(,,, $mFoto[$id]['width']) = getimagesize($mFoto[$id]['small']);
    }
    if (empty($mWallpapers) and empty($mFoto)) {
        return 404;
    }


    $max = ceil($count/cmfConfig::get('site', 'photoPages'));
    $mFotoPageUrl = cmfPagination::generate($pageId > $max ? $max : $pageId, $count, $limit, cmfConfig::get('site', 'photoPages'), create_function('&$page, $k, $v', '
            $page[$k]["url"] = $k==1 ? cmfGetUrl("/wallpapers/") : cmfGetUrl("/wallpapers/page/", array($k));'));

    cmfCache::setParam('wallpapers', $pageId, array($mWallpapers, $mFoto, $mWallpapersPageUrl, $mFotoPageUrl), 'photo');
}

$this->assing2('mWallpapers', $mWallpapers);
$this->assing2('mFoto', $mFoto);
$this->assing2('photoUrl', cmfGetUrl('/photo/'));
$this->assing2('wallpapersUrl', cmfGetUrl('/wallpapers/'));
$this->assing2('vdataUrl', cmfGetUrl('/wallpapers/vdata/'));

if ($mWallpapersPageUrl)
    $this->assing('mWallpapersPageUrl', $mWallpapersPageUrl);
if ($mFotoPageUrl)
    $this->assing('mFotoPageUrl', $mFotoPageUrl);
?>