<?php

cmfGlobal::set('body', 'review');

$infoId = cmfGlobal::get('$infoId');
$newsId = cmfGlobal::get('$param1');
$pageId = cmfGlobal::get('$pageId');
$limit = cmfConfig::get('site', 'reviewsLimit');
$offset = ($pageId - 1) * $limit;
if ($offset > 3000)
    return 404;

if ($mReviews = cmfCache::getParam('reviews', $pageId)) {
    list($mReviews, $mPageUrl) = $mReviews;
} else {
    $sql = cmfRegister::getSql();
    $res = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id, image FROM ?t WHERE visible='yes' ORDER BY `main`, date DESC LIMIT ?i, ?i", db_reviews, $offset, $limit)
            ->fetchAssocAll('id');
    if (empty($res))
        return 404;
    $count = $sql->getFoundRows();
    $list = $sql->placeholder("SELECT id, lang, header, content FROM ?t WHERE id ?@", db_reviews_lang, array_keys($res))
            ->fetchAssocAll('id', 'lang');
    $mReviews = array();
    foreach ($res as $id => $row) {
        cmfLang::data($row, get($list, $id));
        $mReviews[$id] = array(
            'name' => $row['header'],
            'title' => htmlspecialchars($row['header']),
            'image' => cmfBaseImg . cmfPathReviews . $row['image'],
            'content' => $row['content'],
        );
        list(,,, $mReviews[$id]['width']) = getimagesize(cmfWWW . cmfPathReviews . $row['image']);
    }
    $mPageUrl = cmfPagination::generate($pageId, $count, $limit, cmfConfig::get('site', 'reviewsPage'), create_function('&$page, $k, $v', '
            $page[$k]["url"] = $k==1 ? cmfGetUrl("reviews/") : cmfGetUrl("/reviews/page/", array($k));'));

    cmfCache::setParam('reviews', $pageId, array($mReviews, $mPageUrl), 'reviews');
}

$this->assing('mReviews', $mReviews);
if ($mPageUrl)
    $this->assing('mPageUrl', $mPageUrl);
?>