<?php


cmfLoad("FeedWriter/FeedWriter");

//Создаем экземпляр класса FeedWriter.
$newsFeed = new FeedWriter(RSS2);

//Настройка канала элементов
//Использование функции-оболочки для общих элементов канала
$newsFeed->setTitle('Новости сайта');
$newsFeed->setLink(cmfGetUrl('/index/'));
$newsFeed->setDescription('Новости сайта');

$sql = cmfRegister::getSql();

$rss = array();
$limit = cmfConfig::get('site', 'newsLimit');
$res = $sql->placeholder("SELECT id, uri, date FROM ?t WHERE visible='yes' ORDER BY `main`, date DESC LIMIT 0, ?i", db_news, $limit)
        ->fetchAssocAll('id');
$list = $sql->placeholder("SELECT id, lang, header, notice FROM ?t WHERE id ?@", db_news_lang, array_keys($res))
        ->fetchAssocAll('id', 'lang');
foreach ($res as $id=>$row) {
    cmfLang::data($row, get($list, $id));
    $rss[strtotime($row['date'])] = array(
        'header'=>$row['header'],
        'url'=>cmfGetUrl('/news/item/', array($row['uri'])),
        'header'=>date('r', strtotime($row['date'])),
        'notice'=>$row['notice']
    );
}

$limit = cmfConfig::get('site', 'articleLimit');
$res = $sql->placeholder("SELECT id, uri, date FROM ?t WHERE visible='yes' ORDER BY `main`, date DESC LIMIT 0, ?i", db_article, $limit)
        ->fetchAssocAll('id');
$list = $sql->placeholder("SELECT id, lang, header, notice FROM ?t WHERE id ?@", db_article_lang, array_keys($res))
        ->fetchAssocAll('id', 'lang');
foreach ($res as $id=>$row) {
    cmfLang::data($row, get($list, $id));
    $rss[strtotime($row['date'])] = array(
        'header'=>$row['header'],
        'url'=>cmfGetUrl('/articles/item/', array($row['uri'])),
        'header'=>date('r', strtotime($row['date'])),
        'notice'=>$row['notice']
    );
}
krsort($rss);

foreach ($rss as $row) {
    //создаем пустой item
    $newItem = $newsFeed->createNewItem();

    //добавляем в него информацию
    $newItem->setTitle($row['header']);
    $newItem->setLink($row['url']);
    $newItem->setDate($row['date']);
    $newItem->setDescription($row['notice']);

    //теперь добавляем item в наш канал
    $newsFeed->addItem($newItem);
}
//Все готово. Генерируем и выводим получившийся XML
$newsFeed->genarateFeed();

cmfDebug::destroy();
exit;
?>
