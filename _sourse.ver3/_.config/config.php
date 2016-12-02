<?php

// компиляция
// 0 - режим отладки
// при режиме > 0 уже юзается общие скомпилированные файлы для морды и админа
// 1 - юзается общие скомпилированные файлы для морды и админа
// 2 - режим компиляции php файлов
define('cmfComplile', 0);

// определяем домен
define('cmfDomen', 'v8-multilang-yachts.jdjiwi.ru');
define('cmfMainPath', '');

// драйвер для кеша
define('cmfCacheDriver', 'sql');
// конфигурация мемкеша
define('cmfMemcacheHost', 'localhost');
define('cmfMemcachePort', 11211);

define('cmfSphinxHost', 'localhost');
define('cmfSphinxPort', 3312);

// префикс к таблицам
define('cmfDbPefix', 's03_');


// концигурации базы данных
define('cmfMysqlHost', 'localhost');
define('cmfMysqUser', 'v8-multilang-yachts');
define('cmfMysqPassword', 'v8-multilang-yachts');
define('cmfMysqDb', 'v8-multilang-yachts');

// концигурации базы данных
define('cmfSQLiteDb', 'mydb.sq3');


// концигурации базы данных
define('cmfSourse', cmfRoot . '_sourse.ver3/');
define('cmfWWW', cmfRoot . 'public_html/');

// Соль
define('cmfSalt', '6z3WBO4GN8');



//ImageMagick
define('ImageMagickIs', 1);
define('ImageMagickProg', '');
define('ImageMagickPath', '/usr/bin/convert/');
setlocale(LC_ALL, array('ru_RU.utf-8', 'rus_RUS.utf-8'));

date_default_timezone_set('Europe/Moscow');
?>
