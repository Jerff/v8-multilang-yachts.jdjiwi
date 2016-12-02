<?php

$infoId = cmfGlobal::get('$infoId');
if (isset($_POST['searchName'])) {
    $name = $_POST['searchName'];
    $name = preg_replace('~([^a-zа-я0-9])~iu', ' ', $name);

    cmfRedirect(cmfGetUrl('/search/', array(urlencode($name))));
}
$searchUri = cmfPages::getParam(1);

if (!$info = cmfContent::info('search'))
    return 404;
list($info, $request, $headerId, $mPath) = $info;
foreach ($mPath as $key => $value) {
    if (is_string($key)) {
        cmfMenu::add($value, $key);
    } else {
        cmfMenu::add($value);
    }
}
cmfMenu::setRequest($request);
cmfMenu::setSelect('$headerId', $headerId);
cmfMenu::setH1($info['name']);
cmfSeo::set('title', $info['title']);
cmfSeo::set('keywords', $info['keywords']);
cmfSeo::set('description', $info['description']);
cmfGlobal::set('body', 'info');



$sql = cmfRegister::getSql();
$sort = $where = array();
$where['visible'] = 'yes';
if ($searchUri) {
    $name = urldecode($searchUri);
    cmfGlobal::set('$searchName', $name);
    $where[] = 'AND';
    if (strlen($name) > 4) {
        $isSearh = true;
        $where[] = $sql->getQuery("MATCH (`content`) AGAINST (? IN BOOLEAN MODE)", $name);
        $sort['function'] = $sql->getQuery("MATCH (`content`) AGAINST (?) DESC", $name);
    } else {
        $where[] = $sql->getQuery("`content` LIKE '%?s%'", $name);
        $sort[] = 'name';
    }
    $where[] = $sql->getQuery("OR `name`=?", $name);
}

$limit = 15;
$pageId = (int)cmfPages::getParam(3);
if(empty($pageId)) $pageId = 1;
$offset = ($pageId - 1) * $limit;
if ($offset > 3000)
    return 404;

$search = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS page, id, lang, name, notice, url, MATCH (`content`) AGAINST ('$name' IN BOOLEAN MODE) as a2 FROM ?t WHERE ?w GROUP BY id ORDER BY ?o LIMIT ?i, ?i", db_search, $where, $sort, $offset, $limit)
        ->fetchAssocAll('id', 'lang');
if ($search) {
    $count = $sql->getFoundRows();
    cmfLoad('search/function');
    cmfSearcView($search, $searchUri);
    $this->assing('search', $search);



    $mPageUrl = cmfPagination::generate($pageId, $count, $limit, 10/*cmfConfig::get('site', 'articlePages')*/, create_function('&$page, $k, $v', '
    	$page[$k]["url"] = $k==1 ? cmfGetUrl("/search/", array("'. $searchUri .'")) : cmfGetUrl("/search/page/", array("'. $searchUri .'", $k));'));
    if ($mPageUrl)
        $this->assing('mPageUrl', $mPageUrl);

    $this->assing('count', $count);
    $this->assing2('view', count($search));
    $this->assing2('searchName', cmfString::specialchars(cmfGlobal::get('$searchName')));
}
?>
