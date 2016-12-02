<?php

$infoId = 54;//cmfGlobal::get('$infoId');
$newsId = cmfGlobal::get('$param1');
$pageId = cmfPages::getParam(1);//cmfGlobal::get('$pageId');
if (!$pageId) {
   $pageId = 1;
}
if (!$infoId)
    return 404;

if (!$info = cmfContent::info($infoId, true))
    return 404;
list($info, $request, $headerId, $mPath) = $info;
$mMenu = cmfContent::initSubMenu($info, $infoId);
foreach ($mPath as $key => $value) {
    if (is_string($key)) {
        cmfMenu::add($value, $key);
    } else {
        cmfMenu::add($value);
    }
}
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setRMenu($mMenu);
cmfGlobal::set('body', 'info');
cmfSeo::set('title', empty($info['description']) ? $info['name'] : $info['title']);
cmfSeo::set('keywords', empty($info['description']) ? $info['name'] : $info['keywords']);
cmfSeo::set('description', empty($info['description']) ? $info['name'] : $info['description']);

$sql = cmfRegister::getSql();
$limit = cmfConfig::get('site', 'blogLimit');
$offset = ($pageId - 1) * $limit;
if ($offset > 3000)
    return 404;
$res = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id, uri, view, autor, image, `update`, date FROM ?t WHERE visible='yes' ORDER BY `main`, date DESC LIMIT ?i, ?i", db_blog, $offset, $limit)
        ->fetchAssocAll('id');
if (empty($res))
    return 404;
$count = $sql->getFoundRows();
$list = $sql->placeholder("SELECT id, lang, name, notice FROM ?t WHERE id ?@", db_blog_lang, array_keys($res))
        ->fetchAssocAll('id', 'lang');
$_news = $_autor = array();
foreach ($res as $id => $row) {
    cmfLang::data($row, get($list, $id));
    $_news[$id] = array(
        'date' => date('d.m.Y', strtotime($row['date'])),
        'view' => $row['view'],
        'name' => $row['name'],
        'title' => htmlspecialchars($row['name']),
        'notice' => $row['notice'],
        'autor' => $row['autor'],
        'url' => cmfGetUrl('/blog/item/', array($row['uri']))
    );
    if (!empty($row['image'])) {
        $_news[$id]['image'] = cmfImage::preview(cmfPathBlog . $row['image'], 660, 270, $row['name'], 'blog', $row['id'], $row['update']);
        list(,,, $_news[$id]['width']) = getimagesize($_news[$id]['image']);
    }
    $_autor[] = $row['autor'];
}

$_autor = $sql->placeholder("SELECT id, neturl FROM ?t WHERE visible='yes'", db_blog_autor, $_autor)
        ->fetchAssocAll('id');
$list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_blog_autor_lang, array_keys($_autor))
        ->fetchAssocAll('id', 'lang');
foreach ($_autor as $id => $row) {
    cmfLang::data($row, get($list, $id));
    $_autor[$id] = $row;
}

$this->assing('_news', $_news);
$this->assing('_autor', $_autor);

cmfMenu::setH1('');

$mPageUrl = cmfPagination::generate($pageId, $count, $limit, cmfConfig::get('site', 'blogPages'), create_function('&$page, $k, $v', '
    	$page[$k]["url"] = $k==1 ? cmfGetUrl("/blog/") : cmfGetUrl("/blog/page/", array($k));'));
$this->assing('_news', $_news);
if ($mPageUrl)
    $this->assing('mPageUrl', $mPageUrl);


$networkL = cmfConfig::get('site', 'blogFallover');
$networkL = str_replace(
        array('%url%', '%header%'),
        array(cmfPages::getUrl(), cmfMenu::h1()), $networkL);
$this->assing('networkL', $networkL);
?>