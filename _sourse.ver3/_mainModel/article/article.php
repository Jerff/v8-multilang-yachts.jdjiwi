<?php

$infoId = cmfGlobal::get('$infoId');
$newsId = cmfGlobal::get('$param1');
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


$sql = cmfRegister::getSql();
$news = $sql->placeholder("SELECT id, image FROM ?t WHERE id=? AND visible='yes'", db_article, $newsId)
        ->fetchAssoc();
if (!$news)
    return 404;
$list = $sql->placeholder("SELECT lang, header, content, title, keywords, description FROM ?t WHERE id=?", db_article_lang, $newsId)
        ->fetchAssocAll('lang');
cmfLang::data($news, $list);
if (!empty($news['image'])) {
    $news['image'] = cmfBaseImg . cmfPathArticle . $news['image'];
    $news['title'] = htmlspecialchars($news['header']);
}
cmfMenu::add($news['header']);

$res = $sql->placeholder("SELECT image, name FROM ?t WHERE page='news/edit' AND parent=? AND visible='yes' ORDER BY main, pos DESC", db_main_slider, $news['id'])
        ->fetchAssocAll();
$mSlider = array();
foreach ($res as $row) {
    $mSlider[] = array('title' => htmlspecialchars($row['name']),
        'image' => cmfBaseImg . cmfPathMain . $row['image']);
}

cmfContent::replace($this, $news['content'], $mSlider);
cmfSeo::set('title', empty($news['title']) ? $news['header'] : $news['title']);
cmfSeo::set('keywords', empty($news['keywords']) ? $news['header'] : $news['keywords']);
cmfSeo::set('description', empty($news['description']) ? $news['header'] : $news['description']);
$this->assing('news', $news);
?>