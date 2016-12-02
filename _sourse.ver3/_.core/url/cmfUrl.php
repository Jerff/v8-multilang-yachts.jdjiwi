<?php

class cmfUrl {

    static private $mPath = false;

    static public function reform($uri, $param) {
        if (strpos($uri, '{path=') !== false) {
            $path = preg_replace('~(.*\{path=)([a-z_\.]+)(\}.*)~S', '$2', $uri);
            if ($path !== $uri) {
                if (empty(self::$mPath)) {
                    if (!self::$mPath = cmfCache::get('cmfUrl::reform')) {
                        self::$mPath = cmfRegister::getSql()->placeholder("SELECT pages, isUri FROM ?t WHERE pages!='default' AND pages!=''", db_menu_info)
                                ->fetchRowAll(0, 1);
                        cmfCache::set('cmfUrl::reform', self::$mPath, 'url');
                    }
                }
                if (isset(self::$mPath[$path])) {
                    $uri = str_replace('{path=' . $path . '}', self::$mPath[$path], $uri);
                }
            }
        }

        if ($param === null)
            return $uri;
        reset($param);
        while (list($k, $v) = each($param))
            $uri = str_replace('(' . ($k + 1) . ')', $v, $uri);
        return $uri;
    }

}

?>