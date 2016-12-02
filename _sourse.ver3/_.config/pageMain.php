<?php

$p = array();
// --------- служебные ---------
$p['/404/']=array(
't'=>0,
'part'=>'main',
'path'=>'404',
'noUrl'=>true
);

$p['/header/']=array(
'part'=>'main',
'path'=>'header',
'isMain'=>true
);

$p['/menu/']=array(
'part'=>'main',
'path'=>'menu',
'isMain'=>true
);

$p['/info/header/']=array(
'part'=>'main',
'path'=>'info.header',
'isMain'=>true
);

$p['/info/footer/']=array(
'part'=>'main',
'path'=>'info.footer',
'noUrl'=>true
);

$p['/yachts/header/']=array(
'part'=>'main',
'path'=>'yachts.header',
'isMain'=>true
);

$p['/yachts/footer/']=array(
'part'=>'main',
'path'=>'yachts.footer',
'noUrl'=>true
);

$p['/footer/']=array(
'part'=>'main',
'path'=>'footer',
'noUrl'=>true
);

$p['/menu/search/']=array(
'part'=>'main',
'path'=>'main/search'
);
// --------- /служебные ---------

// --------- Главная ---------
$p['/index/']=array(
't'=>1,
'part'=>'main',
'url'=>'/',
'path'=>'main/index',
'noUrl'=>true
);

$p['/index/yachts/']=array(
'part'=>'main',
'path'=>'main/index.main',
'noUrl'=>true
);

$p['/test/']=array(
't'=>1,
'part'=>'main',
'url'=>'/test/',
'path'=>'main/test',
'!cache'=>true,
'noUrl'=>true
);

$p['/info/']=array(
't'=>0,
'part'=>'main',
'url'=>'/(1)/',
'path'=>'main/info',
'Request'=>true
);

$p['/content/']=array(
't'=>0,
'part'=>'main',
'url'=>'/(1)/',
'path'=>'main/content'
);

$p['/map/']=array(
'part'=>'main',
'path'=>'main/map',
'noUrl'=>true
);

$p['/table/one/']=array(
'part'=>'main',
'path'=>'table/one',
'!cache'=>true,
'noUrl'=>true
);
// --------- /Главная ---------

// --------- Новости ---------
$p['/news/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=news}/',
'path'=>'news/list'
);

$p['/news/page/']=array(
'part'=>'main',
'url'=>'/{path=news}/page_(1)/',
'path'=>'news/list'
);

$p['/news/item/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=news}/(1)/',
'path'=>'news/news'
);

$p['/news/rss/']=array(
'part'=>'main',
'url'=>'/news/rss/',
'path'=>'news/rss'
);
// --------- /Новости ---------

// --------- Статьи ---------
$p['/articles/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=articles}/',
'path'=>'article/list'
);

$p['/articles/page/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=articles}/page_(1)/',
'path'=>'article/list'
);

$p['/articles/item/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=articles}/(1)/',
'path'=>'article/article'
);
// --------- /Статьи ---------

// --------- Отзывы ---------
$p['/reviews/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=reviews}/',
'path'=>'reviews/list'
);

$p['/reviews/page/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=reviews}/page_(1)/',
'path'=>'reviews/list'
);

$p['/reviews/brand/']=array(
't'=>0,
'part'=>'main',
'path'=>'reviews/brand'
);
// --------- /Отзывы ---------

// --------- Фото яхт ---------
$p['/photo/']=array(
'part'=>'main',
'url'=>'/{path=photo}/',
'path'=>'photo/photo'
);

$p['/photo/page/']=array(
'part'=>'main',
'url'=>'/{path=photo}/page_(1)/',
'path'=>'photo/photo'
);

$p['/wallpapers/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=wallpapers}/',
'path'=>'photo/wallpapers'
);

$p['/wallpapers/page/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=wallpapers}/page_(1)/',
'path'=>'photo/wallpapers'
);

$p['/selfie/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=selfie}/',
'path'=>'photo/selfie'
);
// --------- /Фото яхт ---------

// --------- Блог ---------
$p['/blog/']=array(
't'=>0,
'part'=>'blog',
'url'=>'/',
'path'=>'blog/list'
);

$p['/blog/page/']=array(
'part'=>'blog',
'url'=>'/page_(1)/',
'path'=>'blog/list'
);

$p['/blog/item/']=array(
't'=>0,
'part'=>'blog',
'url'=>'/(1)/',
'path'=>'blog/blog'
);
// --------- /Блог ---------

// --------- Все верфи мира ---------
$p['/shipyards/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=shipyards}/',
'path'=>'shipyards/list'
);

$p['/shipyards/type/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=shipyards}/(1)/',
'path'=>'shipyards/type'
);

$p['/shipyards/type/list/']=array(
'part'=>'main',
'path'=>'shipyards/typeList'
);

$p['/shipyards/one/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=shipyards}/(1)/',
'path'=>'shipyards/shipyards'
);

$p['/shipyards/yachts/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=shipyards}/(1)/(2)/',
'path'=>'shipyards/yachts'
);
// --------- /Все верфи мира ---------

// --------- Продажа новых яхт ---------
$p['/sale/new/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=new_sale}/',
'path'=>'sale_new/sale'
);

$p['/sale/new/type/']=array(
't'=>2,
'part'=>'main',
'url'=>'/{path=new_sale}/(1)/',
'path'=>'sale_new/type'
);

$p['/sale/new/type/yachts/']=array(
't'=>2,
'part'=>'main',
'path'=>'sale_new/typeYachts'
);

$p['/sale/new/search/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=new_sale}/search/',
'path'=>'sale_new/search',
'noUrl'=>true
);

$p['/sale/new/search/result/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=new_sale}/search/(1)/',
'path'=>'sale_new/search_result'
);

$p['/sale/new/search/result/page/']=array(
'part'=>'main',
'url'=>'/{path=new_sale}/search/(1)/page_(2)/',
'path'=>'sale_new/search_result'
);

$p['/sale/new/search/result/yachts/']=array(
'part'=>'main',
'path'=>'sale_new/search_yachts'
);

$p['/sale/request/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=form.sale}/',
'path'=>'main/info'
);
// --------- /Продажа новых яхт ---------

// --------- Брокераж яхт ---------
$p['/brokerage/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=brokerage}/',
'path'=>'brokerage/brokerage'
);

$p['/brokerage/type/']=array(
't'=>2,
'part'=>'main',
'url'=>'/{path=brokerage}/(1)/',
'path'=>'brokerage/type'
);

$p['/brokerage/type/yachts/']=array(
't'=>2,
'part'=>'main',
'path'=>'brokerage/typeYachts'
);

$p['/brokerage/yachts/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=brokerage}/(1)/(2)/',
'path'=>'brokerage/yachts'
);
// --------- /Брокераж яхт ---------

// --------- Аренда яхт ---------
$p['/arenda/info/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=arenda}/',
'path'=>'arenda/info',
'noUrl'=>true
);

$p['/arenda/info2/']=array(
't'=>0,
'part'=>'main',
'url'=>'/rent_blseam/',
'path'=>'arenda/info',
'noUrl'=>true
);

$p['/charter/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=charter}/',
'path'=>'arenda/charter'
);

$p['/charterm/']=array(
't'=>0,
'part'=>'main',
'url'=>'/charterm/',
'path'=>'arenda/charter'
);

$p['/arenda/']=array(
't'=>0,
'part'=>'main',
'url'=>'/(1)/',
'path'=>'arenda/arenda'
);

$p['/arendaYachts/']=array(
'part'=>'main',
'path'=>'arenda/arendaYachts'
);

$p['/arenda/yachts/']=array(
't'=>0,
'part'=>'main',
'url'=>'/(1)/(2)/',
'path'=>'arenda/yachts'
);

$p['/arenda/request/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=form.arenda}/',
'path'=>'main/info'
);
// --------- /Аренда яхт ---------

// --------- Формы ---------
$p['/form/arenda/']=array(
'part'=>'main',
'path'=>'form/arenda',
'noUrl'=>true,
'Request'=>true
);

$p['/form/sale/']=array(
'part'=>'main',
'path'=>'form/sale',
'noUrl'=>true,
'Request'=>true
);

$p['/form/feedback/']=array(
'part'=>'main',
'path'=>'form/feedback',
'noUrl'=>true
);
// --------- /Формы ---------

// --------- Поиск ---------
$p['/search/']=array(
't'=>0,
'part'=>'main',
'url'=>'/search/(1)/',
'path'=>'search/list'
);

$p['/search/page/']=array(
't'=>0,
'part'=>'main',
'url'=>'/search/(1)/page_(2)/',
'path'=>'search/list'
);
// --------- /Поиск ---------

// --------- Пользователи ---------
$p['/user/register/']=array(
't'=>3,
'part'=>'main',
'url'=>'/user/register/',
'path'=>'user/register',
'noUrl'=>true
);

$p['/user/enter/']=array(
't'=>3,
'part'=>'main',
'url'=>'/user/enter/',
'path'=>'user/enter',
'noUrl'=>true
);

$p['/user/']=array(
't'=>3,
'part'=>'main',
'url'=>'/user/',
'path'=>'user/user',
'brousers'=>false,
'!cache'=>true,
'noUrl'=>true
);

$p['/user/info/']=array(
't'=>3,
'part'=>'main',
'url'=>'/user/info/',
'path'=>'user/info',
'brousers'=>false,
'!cache'=>true,
'noUrl'=>true
);

$p['/user/info/password/']=array(
't'=>3,
'part'=>'main',
'url'=>'/user/info/password/',
'path'=>'user/info.password',
'brousers'=>false,
'!cache'=>true,
'noUrl'=>true
);

$p['/user/exit/']=array(
't'=>3,
'part'=>'main',
'url'=>'/user/exit/',
'path'=>'user/exit',
'brousers'=>false,
'!cache'=>true,
'noUrl'=>true
);

$p['/user/command/']=array(
't'=>3,
'part'=>'main',
'url'=>'/user/command/(1)/',
'path'=>'user/command'
);

$p['/user/password/']=array(
't'=>3,
'part'=>'main',
'url'=>'/user/password/',
'path'=>'user/password',
'noUrl'=>true
);


// --------- Доска объявлений ---------
$p['/board/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=board}/',
'path'=>'board/list'
);

$p['/board/page/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=board}/page_(1)/',
'path'=>'board/list'
);

$p['/board/yachts/']=array(
'part'=>'main',
'path'=>'/board/boardYachts'
);

$p['/board/item/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=board}/(1)/',
'path'=>'board/board'
);

$p['/board/add/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=board}/add/',
'path'=>'board/add'
);

$p['/board/edit/']=array(
't'=>0,
'part'=>'main',
'url'=>'/{path=board}/edit/(1)/',
'path'=>'board/edit'
);

$p['/user/board/']=array(
't'=>0,
'part'=>'main',
'url'=>'/user/board/',
'path'=>'user/board/list'
);

$p['/user/board/page/']=array(
't'=>0,
'part'=>'main',
'url'=>'/user/board/page_(1)/',
'path'=>'user/board/list'
);

$p['/user/board/yachts/']=array(
'part'=>'main',
'path'=>'/user/board/boardYachts'
);
// --------- /Доска объявлений ---------
// --------- /Пользователи ---------

// --------- router ---------
$p['router']=array(
't'=>1,
'part'=>'main',
'path'=>'main/router'
);

$p['router']=array(
't'=>1,
'part'=>'blog',
'path'=>'main/router'
);
// --------- /router ---------

$n = array();
$n['main']['/']='/index/';
$n['main']['/test/']='/test/';
$n['main']['/{path=news}/']='/news/';
$n['main']['/news/rss/']='/news/rss/';
$n['main']['/{path=articles}/']='/articles/';
$n['main']['/{path=reviews}/']='/reviews/';
$n['main']['/{path=photo}/']='/photo/';
$n['main']['/{path=wallpapers}/']='/wallpapers/';
$n['main']['/{path=selfie}/']='/selfie/';
$n['blog']['/']='/blog/';
$n['main']['/{path=shipyards}/']='/shipyards/';
$n['main']['/{path=new_sale}/']='/sale/new/';
$n['main']['/{path=new_sale}/search/']='/sale/new/search/';
$n['main']['/{path=form.sale}/']='/sale/request/';
$n['main']['/{path=brokerage}/']='/brokerage/';
$n['main']['/{path=arenda}/']='/arenda/info/';
$n['main']['/rent_blseam/']='/arenda/info2/';
$n['main']['/{path=charter}/']='/charter/';
$n['main']['/charterm/']='/charterm/';
$n['main']['/{path=form.arenda}/']='/arenda/request/';
$n['main']['/user/register/']='/user/register/';
$n['main']['/user/enter/']='/user/enter/';
$n['main']['/user/']='/user/';
$n['main']['/user/info/']='/user/info/';
$n['main']['/user/info/password/']='/user/info/password/';
$n['main']['/user/exit/']='/user/exit/';
$n['main']['/user/password/']='/user/password/';

$n['main']['/{path=board}/']='/board/';
$n['main']['/{path=board}/add/']='/board/add/';
$n['main']['/user/board/']='/user/board/';
$pr = array();
$pr['blog']['/blog/']=array('#^/page_([0-9]+)/$#');
$pr['main']['/sale/new/search/result/']=array('#^/sale_new/search/([^/]+)/(page_([0-9]+)/)?$#');
$pr['main']['/arenda/info2/']=array('#^/rent_blseam/page/([0-9]+)$#');
$pr['main']['/search/']=array('#^/search/([^/]*)/(page_([0-9]+)/)?$#');
$pr['main']['/search/page/']=array('#^/search/(.*)/$#');
$pr['main']['/user/']=array('#^/user/page_([0-9]+)/$#');
$pr['main']['/user/command/']=array('#^/user/(command/.*)/$#');

$pr['main']['/board/add/']=array('#^/board/(add)/$#');
$pr['main']['/board/edit/']=array('#^/board/edit/([0-9]+)/$#');
$pr['main']['/user/board/']=array('#^/user/board/page_([0-9]+)/$#');
$pr['main']['router']=array('#^(/(.*?(/([^/]+))?)/)(search/([^/]+)/)?(page_([0-9]+)/)$#','#^(/(.*?(/([^/]+))?)/)(search/([^/]+)/)?$#');
$pr['blog']['router']=array('#^(/(.*?(/([^/]+))?)/)(search/([^/]+)/)?(page_([0-9]+)/)$#','#^(/(.*?(/([^/]+))?)/)(search/([^/]+)/)?$#');

$t = array();
$t[0] = 'main.info.php';
$t[1] = 'main.index.php';
$t[2] = 'main.yachts.php';
$t[3] = 'user.index.php';
cmfPages::select('(/(ru|en))?', $p, $n, $pr, $t);

?>