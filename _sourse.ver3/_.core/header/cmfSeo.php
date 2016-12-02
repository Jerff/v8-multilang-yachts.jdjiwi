<?php

class cmfSeo {

    private static $seo = array();
    private static $lang = 'ru';

    public static function set($n, $v) {
        self::$seo[$n] = strip_tags($v);
    }

    public static function get() {
        $seo = self::$seo;
        self::$seo = null;
        return $seo;
    }

    public static function setLang($lang) {
        self::$lang = $lang;
    }

    public static function getData() {

        /* if($r = cmfCache::getRequest('cmfSeo::getTitleMeta')) {
          return $r;
          }
          unset($r); */

        $dt = $dk = $dd = $t = $k = $k = '';
        $res = cmfRegister::getSql()->placeholder("SELECT `uri`, `title`, `keywords`, `description` FROM ?t WHERE `uri` IN('default', '?s') AND lang=?", db_seo_title, cmfPages::getMain(), cmfLang::getId());
        while ($row = $res->fetchAssoc()) {
            if ($row['uri'] === 'default') {
                $dt = $row['title'];
                $dk = $row['keywords'];
                $dd = $row['description'];
                if (!isset($t) and !empty($row['title']))
                    $t = $row['title'];
                if (!isset($k) and !empty($row['keywords']))
                    $k = $row['keywords'];
                if (!isset($d) and !empty($row['description']))
                    $d = $row['description'];
            } else {
                if (!empty($row['title']))
                    $t = $row['title'];
                if (!empty($row['keywords']))
                    $k = $row['keywords'];
                if (!empty($row['description']))
                    $d = $row['description'];
            }
        }

        unset($res, $row);

        if (!isset($t))
            $t = '';
        if (!isset($k))
            $k = '';
        if (!isset($d))
            $d = '';

        $s = self::get();
        while (list($n, $v) = each($s)) {
            $n = '{' . $n . '}';
            if ($n == '{title}')
                $title = $v;
            if ($n == '{keywords}')
                $keywords = $v;
            list($t, $k, $d, $dt, $dk, $dd) = str_replace($n, $v, array($t, $k, $d, $dt, $dk, $dd));
        }
        if (empty($t))
            $t = $dt;
        if (empty($k))
            $k = $dk;
        if (empty($d))
            $d = $dd;

        $r = array($t, $k, $d, self::$lang);
        $keysegm = explode(',', $keywords);
        $rs = self::getSeo($title, $keysegm[0]);
        if ($rs) {
            $rs[] = self::$lang;
            return $rs;
        }

        cmfCache::setRequest('cmfSeo::getTitleMeta', $r, 'seoTitle');
        return $r;
    }

}

?>
