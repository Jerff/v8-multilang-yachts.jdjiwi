<?php
$url = $_SERVER['REQUEST_URI'];

if($url=='/news/') {

$add_title = 'Последние новости компании NSK-Yachts - чартерная компания NSK-Yachts';
$add_desc = 'Свежие новости в сфере аренды, продажи и покупки яхт на сайте NSK-Yachts.com.ua. Новости компании, а также новости в мире';
$add_key = 'новости, компания NSK-Yachts, аренда яхт, продажа яхт';
$add_h1 = 'Новости';



} elseif($url=='/photo/') {

$add_title = 'Фотографии яхт, модели и их характеристики - чартерная компания NSK-Yachts';
$add_desc = 'Каталог яхт - фотографии и характеристики. Широкий выбор яхт и катеров. Контактные телефоны: Tel. +38 (044) 383-64-46, Mob. +38 (094) 928-34-46';
$add_key = 'фотографии яхт, модели, NSK-Yachts';
$add_h1 = 'ФОТОГРАФИИ ЯХТ';


} elseif($url=='/map/') {

$add_title = 'Карта сайта - чартерная компания NSK-Yachts';
$add_desc = 'Карта сайта, удобная и быстрая навигация по разделам. ';
$add_key = 'карта сайта, навигация';
$add_h1 = 'Карта сайта';



} elseif($url=='/aboutus/') {

$add_title = 'О компании, информация - чартерная компания NSK-Yachts';
$add_desc = 'Чартерная компания NSK-Yachts является лидером на украинском рынке аренды яхт. Мы предоставляем самые сложные и самые большие чартеры яхт.';
$add_key = 'О компании, NSK-Yachts';
$add_h1 = 'О КОМПАНИИ';



} elseif($url=='/contacts/') {

$add_title = 'Контактная информация, контакты, телефоны - чартерная компания NSK-Yachts';
$add_desc = 'Контактная информация для связи с нами. Мы предоставляем самые сложные и самые большие чартеры яхт.';
$add_key = 'контакты, телефоны, NSK-Yachts';
$add_h1 = 'КОНТАКТЫ';



} elseif($url=='/inquiry_rent/') {

$add_title = 'Запрос на аренду яхт и катеров - чартерная компания NSK-Yachts';
$add_desc = 'Подать запрос на аренду яхт и катеров в Украине. Мы предоставляем самые сложные и самые большие чартеры яхт, отличные цены и отличное качество';
$add_key = 'аренда яхт, заказ яхт, аренда яхт и катеров, заказ яхт и катеров';
$add_h1 = 'ЗАПРОС НА АРЕНДУ ЯХТ И КАТЕРОВ';




} elseif($url=='/dopolnitelnyj_servis/') {

$add_title = 'Дополнительный сервис при аренде яхт - чартерная компания NSK-Yachts';
$add_desc = 'Наша компания предлагает дополнительный сервис к услуге аренды яхт в Одессе, Киеве, Крыму.';
$add_key = 'аренда яхт, дополнительный сервис, заказ яхт, аренда яхт и катеров';
$add_h1 = 'ДОПОЛНИТЕЛЬНЫЙ СЕРВИС';



} elseif($url=='/gotovye_predloghenija/') {
$add_title = 'Готовые предложения - чартерная компания NSK-Yachts';
$add_desc = 'Готовые предложения по аренде яхт в Украине и за рубежом. Мы предоставляем самые сложные и самые большие чартеры яхт, отличные цены и отличное качество';
$add_key = 'аренда, готовые, предложения, цена аренды';
$add_h1 = 'ГОТОВЫЕ ПРЕДЛОЖЕНИЯ';
} elseif(strpos($url,'/shipyard/')!==false) {
    $arr = explode('/',$url);
    $len = count($arr);
    $newarr = array();
    for($i=0;$i<$len;$i++){
        if($arr[$i]!='') $newarr[] = $arr[$i];
    }
    $arr = $newarr;
    unset($newarr);
    
    $len = count($arr);
    
    mysql_connect('localhost', 'user3020_yacnew', 'WrKlX3NQ');
    mysql_select_db('user3020_yacnew');
    mysql_query('SET NAMES "utf8"');
       
    if($len==2) {
        $res = mysql_query('SELECT name FROM s03_shipyards WHERE `uri`="'.$arr[1].'"');
        $arc = mysql_fetch_row($res);
        $name = $arc[0];
        if($arr[1]=='megayachty') {
            $add_title = 'Верфи мира, мегаяхты - чартерная компания NSK-Yachts';
        }
        elseif($arr[1]=='superyachty')
            $add_title = 'Верфи мира, суперяхты - чартерная компания NSK-Yachts';
        elseif($arr[1]=='motoryachty')
            $add_title = 'Верфи мира, моторные яхты - чартерная компания NSK-Yachts';
        elseif($arr[1]=='katera')
            $add_title = 'Верфи мира, катера - чартерная компания NSK-Yachts';
        else  
        $add_title = 'Верфи мира: '.$name.' - чартерная компания NSK-Yachts';
        $add_desc = 'Обзор верфи '.$name.', яхты и катера, которые они производят. Популярные яхтенные бренды, верфи, занимающиеся строительсвом суперяхт и мегаяхт.';
        $add_key = 'верфи, '.$name.', яхты, катера';
        $add_h1 = $name;
    }
    elseif($len==3) {
        $res = mysql_query('SELECT name FROM s03_shipyards_yachts WHERE `uri`="'.$arr[2].'"');
        $arc = mysql_fetch_row($res);
        $name = $arc[0];
        
        $res = mysql_query('SELECT name FROM s03_shipyards WHERE `uri`="'.$arr[1].'"');
        $arc = mysql_fetch_row($res);
        $brand = $arc[0];

        $add_title = 'Верфь '.$brand.', модель '.$name.' - чартерная компания NSK-Yachts';
        $add_desc = 'Обзор верфи '.$name.', яхты и катера, которые они производят. Популярные яхтенные бренды, верфи, занимающиеся строительсвом суперяхт и мегаяхт.';
        $add_key = 'верфи, '.$brand.', '.$name.', яхты, катера';
    }
} elseif(strpos($url,'/brokerage/motoryachty/')!==false) {
    $arr = explode('/',$url);
    $len = count($arr);
    $newarr = array();
    for($i=0;$i<$len;$i++){
        if($arr[$i]!='') $newarr[] = $arr[$i];
    }
    $arr = $newarr;
    unset($newarr);
    
    $len = count($arr);
    
    mysql_connect('localhost', 'yachts_new', 'WrKlX3NQ');
    mysql_select_db('yachts_new');
    mysql_query('SET NAMES "utf8"');
    
    if($len==3) {
        $res = mysql_query('SELECT name FROM s03_brokerage_yachts WHERE `uri`="'.$arr[2].'"');
        $arc = mysql_fetch_row($res);
        $name = $arc[0];

        $add_title = 'Продажа б у яхты '.$name.' - чартерная компания NSK-Yachts';
        $add_desc = 'Продажа брокеражных моторных яхт, бу яхта '.$name.'. Купить брокеражную яхту с помощью компании NSK-Yachts';
        $add_key = 'моторные яхты брокераж, брокераж, яхты, б у моторная яхта, б/у';
        $add_h1 = $name;
    }
}
?>
