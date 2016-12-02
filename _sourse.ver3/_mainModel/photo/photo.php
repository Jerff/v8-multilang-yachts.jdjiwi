<?php

$pageId = cmfGlobal::get('$pageId');
cmfCookie::set('idFotoWallpapers', 'foto');
if($pageId==1) {
    cmfRedirectSeo(cmfGetUrl("/wallpapers/"));
} else {
    cmfRedirectSeo(cmfGetUrl("/wallpapers/page/", array($pageId)));
}


$limit = cmfConfig::get('site', 'photoLimit');
$offset = ($pageId - 1) * $limit;
if ($offset > 3000)
    return 404;

if ($mFoto = cmfCache::getParam('photo', $pageId)) {
    list($mFoto, $mPageUrl) = $mFoto;
} else {

    $sql = cmfRegister::getSql();
    $res = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id, image_main, image_small FROM ?t WHERE visible='yes' ORDER BY main, pos DESC LIMIT ?i, ?i", db_foto, $offset, $limit)
            ->fetchAssocAll('id');
    if (empty($res))
        return 404;
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
            'small' => cmfImage::preview(cmfBaseImg . cmfPathFoto . $row['image_main'], 170, 97,$title, $row['id'])
        );
        list(,,, $mFoto[$id]['width']) = getimagesize($mFoto[$id]['small']);
    }
    $mPageUrl = cmfPagination::generate($pageId, $count, $limit, cmfConfig::get('site', 'photoPages'), create_function('&$page, $k, $v', '
            $page[$k]["url"] = $k==1 ? cmfGetUrl("/photo/") : cmfGetUrl("/photo/page/", array($k));
    '));

    cmfCache::setParam('photo', $pageId, array($mFoto, $mPageUrl), 'photo');
}

$this->assing2('mFoto', $mFoto);
$this->assing2('photoUrl', cmfGetUrl('/photo/'));
$this->assing2('wallpapersUrl', cmfGetUrl('/wallpapers/'));
//$this->assing2('vdataUrl', cmfGetUrl('/photo/vdata/'));

if ($mPageUrl)
    $this->assing('mPageUrl', $mPageUrl);
?>