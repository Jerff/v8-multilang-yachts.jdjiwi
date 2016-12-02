<?php

class cmfHeader {

    private static $data = array('versionCss' => array(),
        'versionJs' => array(),
        'version' => '',
        'js' => array(),
        'jsCompile' => array(),
        'css' => array(),
        'cssCompile' => array(),
        'meta' => array());

    public static function setVersionJs($v) {
        self::$data['versionJs'][] = cmfBaseUrl . $v;
    }

    public static function setVersionCss($v) {
        self::$data['versionCss'][] = cmfBaseUrl . $v;
    }

    public static function setVersion($v) {
        self::$data['version'] = $v;
    }

    public static function setJs($js, $base = null) {
        self::$data['js'][] = ($base ? $base : cmfBaseUrl) . $js;
    }

    public static function setJsSourse($js) {
        self::$data['js'][] = $js;
    }

    public static function setJsCompile($js, $base = null) {
        self::$data['jsCompile'][] = ($base ? $base : cmfBaseUrl) . $js;
    }

    public static function setCss($css, $base = null) {
        self::$data['css'][] = ($base ? $base : cmfBaseUrl) . $css;
    }

    public static function setCssCompile($css, $base = null) {
        self::$data['cssCompile'][] = ($base ? $base : cmfBaseUrl) . $css;
    }

    public static function setMeta($value) {
        self::$data['meta'][] = $value;
    }

    public static function get() {
        $data = self::$data;
        self::$data = null;
        return $data;
    }

    public static function getTitleMeta() {
        list($t, $k, $d, $l) = cmfSeo::getData();
        $head = '
    <title>' . $t . '</title>
    <meta name="keywords" content="' . $k . '"/>
    <meta name="description" content="' . $d . '"/>
    <meta http-equiv="Content-Language" content="' . $l . '" />';

        $p = cmfPages::getPage(cmfPages::getMain());
        if (isset($p['!cache'])) {
            $head .= '
    <meta http-equiv="pragma" content="no-cache"/>
    <meta http-equiv="cache-control" content="no-cache"/>';
            header('Pragma: no-cache');
            header('Cache-Control: no-cache');
        } else {
            $days = 1;
            $day = 24 * 60 * 60;
            $add = ((rand(9, 19)) * 60 + rand(1, 59)) * 60 + rand(1, 59);
            $time = gmdate('D, d M Y H:i:s', (floor(time() / $day) - $days) * $day + $add) . ' GMT';
            $head .= '
    <meta http-equiv="last-modified" content="' . $time . '"/>';
            header('Last-Modified: ' . $time);
        }
        return $head;
    }

    static private $isBase = true;

    static public function notBase() {
        self::$isBase = false;
    }

    static public function getRobots() {
        $url = $_SERVER['REQUEST_URI'];
        $fragments = array('/user/', '/search/', 'type=', '/articles/page_', '/news/page_', '/photo/page_', '/wallpapers/page_');
        foreach ($fragments as $fragment)
            if (strpos($url, $fragment) !== false) {
                return "\n<meta name=\"robots\" content=\"noindex, follow\">\n";
            }
        return '';
    }

    static public function getCustomHead() {
        $res = cmfRegister::getSql()->placeholder("SELECT `head` FROM ?t WHERE `type`=? and `url`=?", db_seo_head, cmfPart, $_SERVER['REQUEST_URI']);
        if ($row = $res->fetchAssoc()) {
            return $row['head'];
        } else {
            return '';
        }
    }

    static public function getHead() {
        $head = '';
        if (self::$isBase) {
            $head = '
    <base href="/"/>';
        }
        $head .= '
    <meta content="text/html; charset=' . cmfCharset . '" http-equiv="Content-Type"/>';

        extract(self::get());
        // генерим мета заданные на сайте
        if ($meta) {
            while (list($value) = each($meta)) {
                $head .= '
    <meta';
                while (list($k, $v) = each($value)) {
                    $head .= ' ' . $k . '="' . $v . '"';
                }
                $head .= '/>';
            }
        }

        // генерим css и js
        if ($versionJs) {
            foreach ($versionJs as $v) {
                $head .= '
    <script type="text/javascript" src="' . $v . '?v=' . $version . '"></script>';
            }
        } else {
            $js = array_merge($jsCompile, $js);
        }

        if ($versionCss) {
            foreach ($versionCss as $v) {
                $head .= '
    <link href="' . $v . '?v=' . $version . '" rel="stylesheet" type="text/css" />';
            }
        } else {
            $css = array_merge($cssCompile, $css);
        }

        while (list(, $v) = each($css)) {
            $head .= '
    <link href="' . $v . '?v=' . $version . '" rel="stylesheet" type="text/css" />';
        }
        while (list(, $v) = each($js)) {
            $head .= '
    <script type="text/javascript" src="' . $v . '?v=' . $version . '"></script>';
        }
        return $head;
    }

}

?>
