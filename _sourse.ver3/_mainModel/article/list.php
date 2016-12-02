<?php

$infoId = cmfGlobal::get('$infoId');
$newsId = cmfGlobal::get('$param1');
$pageId = cmfGlobal::get('$pageId');
$sql = cmfRegister::getSql();
$limit = cmfConfig::get('site', 'articleLimit');
$offset = ($pageId - 1) * $limit;
if ($offset > 3000)
    return 404;
$res = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id, uri, date FROM ?t WHERE visible='yes' ORDER BY `main`, date DESC LIMIT ?i, ?i", db_article, $offset, $limit)
        ->fetchAssocAll('id');
if(empty($res)) return 404;
$count = $sql->getFoundRows();
$list = $sql->placeholder("SELECT id, lang, header, notice FROM ?t WHERE id ?@", db_article_lang, array_keys($res))
        ->fetchAssocAll('id', 'lang');
$_news = array();
foreach ($res as $id => $row) {
    cmfLang::data($row, get($list, $id));
    $_news[$id] = array('date' => date('d.m.Y', strtotime($row['date'])),
        'header' => $row['header'],
        'notice' => $row['notice'],
        'url' => cmfGetUrl('/articles/item/', array($row['uri'])));
}
$this->assing('_news', $_news);

$mPageUrl = cmfPagination::generate($pageId, $count, $limit, cmfConfig::get('site', 'articlePages'), create_function('&$page, $k, $v', '
    	$page[$k]["url"] = $k==1 ? cmfGetUrl("/articles/") : cmfGetUrl("/articles/page/", array($k));'));
$this->assing('_news', $_news);
if ($mPageUrl)
    $this->assing('mPageUrl', $mPageUrl);
?>