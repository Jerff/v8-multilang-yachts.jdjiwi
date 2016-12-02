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
$news = $sql->placeholder("SELECT * FROM ?t WHERE id=? AND visible='yes'", db_blog, $newsId)
        ->fetchAssoc();
if (!$news)
    return 404;
$list = $sql->placeholder("SELECT lang, name, content, title, keywords, description FROM ?t WHERE id=?", db_blog_lang, $newsId)
        ->fetchAssocAll('lang');
cmfLang::data($news, $list);

//pre($news['content']);
function grge346rg($matches) {
    if(strpos($matches[3], cmfProjectMain)!==0 and strpos($matches[3], cmfProjectBlog)!==0 and strpos($matches[3], 'http')===0) {
        return '<noindex>' . str_replace('<a', '<a rel="nofollow" ', $matches[0]) .'</noindex>';
    } else {
    }
    return $matches[0];
}
$news['content'] = preg_replace_callback('~(<a[^>]+href=("([^"]+)"|\'([^\']+)\'))([^>]+>.*</a>)~iUsS', 'grge346rg', $news['content']);
$news['date'] = date('d.m.Y', strtotime($news['date']));
if (!empty($news['image'])) {
    $news['image'] = cmfImage::preview(cmfPathBlog . $news['image'], 660, 270, $news['name'], 'blog-m', $news['id'], $news['update']);
    list(,,, $news['width']) = getimagesize($news['image']);
}
$autor = $sql->placeholder("SELECT * FROM ?t WHERE id=? AND visible='yes'", db_blog_autor, $news['autor'])
        ->fetchAssoc();
$list = $sql->placeholder("SELECT lang, name FROM ?t WHERE id=?", db_blog_autor_lang, $autor['id'])
        ->fetchAssocAll('lang');
cmfLang::data($autor, $list);
cmfMenu::add($news['name']);



cmfSeo::set('title', empty($news['title']) ? $news['name'] : $news['title']);
cmfSeo::set('keywords', empty($news['keywords']) ? $news['name'] : $news['keywords']);
cmfSeo::set('description', empty($news['description']) ? $news['name'] : $news['description']);
$this->assing('news', $news);
$this->assing('autor', $autor);

$networkL = cmfConfig::get('site', 'blogFallover');
$networkL = str_replace(
        array('%url%', '%header%'), array(cmfPages::getUrl(), cmfMenu::h1()), $networkL);
$this->assing('networkL', $networkL);
?>