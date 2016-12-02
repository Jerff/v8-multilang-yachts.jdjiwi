<?php

cmfLoad('lang/cmfLangConfig');

function word($word) {
    if (cmfLang::getFirst() == cmfLang::getId()) {
        return $word;
    } else {
        return cmfLang::word($word);
    }
}

class cmfLang {

    static private $pregUrl = '';
    static private $first = '';
    static private $list = array();
    static private $key = array();
    static private $id = 1;
    static private $lang = 'ru';
    static private $word = array();

    static private function init() {
        if (!empty(self::$list))
            return;

        if (self::$list = cmfCache::get('cmfLang::init')) {
            list(self::$list, self::$key, self::$first, self::$id, self::$lang, self::$pregUrl) = self::$list;
        } else {

            self::$list = cmfRegister::getSql()->placeholder("SELECT id, name, uri, image, small FROM ?t ORDER BY pos", db_lang)
                    ->fetchAssocAll('id');
            self::$first = self::$id = key(self::$list);
            self::$pregUrl = array();
            foreach (self::$list as $k => $v) {
                self::$key[$v['uri']] = $k;
                self::$pregUrl[] = $v['uri'];
                self::$list[$k]['url'] = self::$first == $k ? '' : '/' . $v['uri'];
            }
            self::$lang = self::$list[$k]['url'];
            self::$pregUrl = '(/(' . implode('|', self::$pregUrl) . '))?';
//            self::$key = cmfRegister::getSql()->placeholder("SELECT id, uri FROM ?t ORDER BY pos", db_lang)
//                    ->fetchRowAll(1, 0);
            cmfCache::set('cmfLang::init', array(self::$list, self::$key, self::$first, self::$id, self::$lang, self::$pregUrl), 'lang');
        }
    }

    static public function word($key) {
        if (empty(self::$word)) {

            if (!self::$word = cmfCache::get('cmfLang::word')) {
                self::$word = cmfRegister::getSql()->placeholder("SELECT name, en FROM ?t", db_lang_word)
                        ->fetchRowAll(0, 1);
                cmfCache::set('cmfLang::word', self::$word, 'lang');
            }
        }
        return get(self::$word, $key, $key);
    }

    static public function getPregUrl() {
        self::init();
        return self::$pregUrl;
    }

    static public function select($lang) {
        self::init();
        foreach (self::$list as $k => $v) {
            if ($v['uri'] == $lang) {
                self::setId($k);
                break;
            }
        }
    }

    static public function setId($id) {
        self::init();
        self::$lang = get2(self::$list, $id, 'url');
        self::$id = $id;
    }

    static public function selectLang($lang) {
        self::init();
        self::$id = get(self::$key, $lang, self::$first);
        self::$lang = get2(self::$list, self::$id, 'url');
    }

    static public function getId() {
        return self::$id;
    }

    static public function getLang() {
        return self::$list[self::$id]['uri'];
    }

    static public function getFirst() {
        self::init();
        return self::$first;
    }

    static public function getUri($lang = null) {
        self::init();
        return get2(self::$list, empty($lang) ? self::$id : $lang, 'url');
    }

    static public function getLangList() {
        self::init();
        return self::$list;
    }

    static public function getAdminList() {
        self::init();
        $lang = self::getId();
        $list = array();
        $param = cmfRequest::viewParam(cmfRegister::getRequest()->getGetAll());
        $url = cmfAjax::getUrl();
        preg_match('~' . preg_quote(cmfAdminUrl) . cmfLang::getPregUrl() . '(.*)\#(\&?.*)~', $url, $tmp);
        $param = get($tmp, 4);
        foreach (self::$list as $k => $v) {
            self::setId($k);
            $list[$k] = array('title' => cmfString::specialchars($v['name']),
                'image' => cmfBaseImg . cmfPathLang . $v['image'],
                'url' => cmfGetAdminUrl(cmfPages::getMain()) . $param);
        }
        self::setId($lang);
        $list[self::getId()]['sel'] = true;
        return $list;
    }

    static public function getMainList() {
        self::init();
        $list = array();
        foreach (self::$list as $k => $v) {
            $list[$k] = array('title' => cmfString::specialchars($v['name']),
                'name' => ucwords($v['uri']),
                'uri' => $v['uri'],
                'image' => cmfPathLang . $v['small'],
                'url' => cmfChangeLang($k, cmfPages::url()));
        }
        $list[self::getId()]['sel'] = true;
        return $list;
    }

    static private function get($info, $key, $key2 = null) {
        if (!isset($info[$key])) {
            $key = key($info);
        }
        return is_null($key2) ? get($info, $key) : get2($info, $key, $key2);
    }

    static private function getKey($info) {
        if (isset($info[self::getId()])) {
            $key = self::getId();
        } else {
            if (isset($info[self::$first])) {
                $key = self::$first;
            } else {
                $key = key($info);
            }
        }
        return $key;
    }

    static public function result(&$info, $list, $prefix = '') {
        self::init();
        if (!$list)
            return;
        $key = self::getKey($list);
        if (isset($list[$key])) {
            foreach ($list[$key] as $k => $v) {
                if ($key == self::getId()) {
                    $info[$k] = empty($v) ? $prefix . self::get($list, self::$first, $k) : $v;
                } else {
                    $info['lang'][$k] = $prefix . $v;
//                    $info[$k] = $prefix . $v;
                }
            }
        }
    }

    static public function data(&$info, $list, $prefix = '') {
        self::init();
        if (!$list)
            return;
        $key = self::getKey($list);
        if (isset($list[$key])) {
            foreach ($list[$key] as $k => $v) {
                if ($key == self::getId()) {
                    $info[$k] = empty($v) ? $prefix . self::get($list, self::$first, $k) : $v;
                } else {
//                    $info['lang'][$k] = $prefix . $v;
                    $info[$k] = $prefix . $v;
                }
            }
        }
    }

    static public function view(&$info, $k) {
        return isset($info[$k]) ? $info[$k] : get2($info, 'lang', $k, '');
    }

    static public function resultList($row, $prefix = '') {
        self::init();
        $info = array();
        $key = self::getKey($row);
        if (isset($row[$key])) {
            foreach ($row[$key] as $k => $v) {
                if ($key == self::getId()) {
                    $info[$k] = empty($v) ? self::get($row, self::$first, $k) : $v;
                } else {
                    $info['lang'][$k] = $v;
                }
            }
        }
        return $info;
    }

    static public function completeList(&$info, $list, $fileds, $prefix = '') {
        self::init();
        if (!$list)
            return;
        $key = self::getKey($list);
        if (isset($list[$key])) {
            foreach ($list[$key] as $k => $v)
                if (isset($fileds[$k])) {
                    if ($key == self::getId()) {
                        $info[$k] = empty($v) ? $prefix . self::get($list, self::$first, $k) : $v;
                    } else {
                        $info[$k] = $prefix . $v;
                    }
                }
        }
    }

    /*
      ALTER TABLE `s03_article_lang` ADD `lang` TINYINT UNSIGNED NOT NULL DEFAULT '1' AFTER `id`
      ALTER TABLE `s03_article_lang` DROP PRIMARY KEY,  ADD PRIMARY KEY ( `id` , `lang` )
     */
}

?>