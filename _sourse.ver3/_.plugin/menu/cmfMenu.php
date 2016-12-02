<?php

cmfLoad('menu/cmfMenuView');
cmfLoad('menu/cmfMenuConfig');

class cmfMenu {

    static private $menu = array();
    const searchUrl = '/prodaga-yacht/search/';
    const searchUri = 'prodaga-yacht/search';

    static public function add($name, $url = null) {
        if($url==self::searchUrl) {
            $url = '';
        } else {
            self::$menu[$name] = $url;
        }
    }

    static private $select = '';

    static public function setSelect($id, $value) {
        self::$select[$id] = $value;
    }

    static public function select($id, &$value) {
        if (!isset(self::$select[$id]))
            return;
        if (isset($value[self::$select[$id]])) {
            $value[self::$select[$id]]['sel'] = 1;
        }
    }

    static public function isSubMenu() {
        return (bool) self::$menu;
    }

    static public function viewSubMenu() {
        if (self::$menu) {
            cmfMenuView::viewSubMenu(cmfGetUrl('/index/'), self::$menu);
        }
    }

    static public function getHeader() {
        $res = cmfRegister::getSql()->placeholder("SELECT id, level, class, menu, url FROM ?t WHERE visible='yes' ORDER BY pos", db_menu)
                ->fetchAssocAll('id');
        $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_menu_lang, array_keys($res))
                ->fetchAssocAll('id', 'lang');
        $_menu = array();
        foreach ($res as $id => $row) {
            cmfLang::data($row, get($list, $id));
            self::getUrl($_menu, $row);
        }
        return $_menu;
    }

    static public function getRigth($level) {
        $res = cmfRegister::getSql()->placeholder("SELECT id, level, class, menu, url FROM ?t WHERE level=? AND visible='yes' ORDER BY pos", db_menu_rigth, $level)
                ->fetchAssocAll('id');
        $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_menu_rigth_lang, array_keys($res))
                ->fetchAssocAll('id', 'lang');
        $_menu = array();

//        cmfRegister::getSql()->placeholder('SELECT 1');
        foreach ($res as $id => $row) {
            cmfLang::data($row, get($list, $id));
            self::getUrlRigth($_menu, $row);
        }
        return $_menu;
    }

    static public function getFooter() {
        $res = cmfRegister::getSql()->placeholder("SELECT id, level, class, menu, url FROM ?t WHERE visible='yes' ORDER BY pos", db_menu_footer)
                ->fetchAssocAll('id');
        $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_menu_footer_lang, array_keys($res))
                ->fetchAssocAll('id', 'lang');
        $_menu = array();
        foreach ($res as $id => $row) {
            cmfLang::data($row, get($list, $id));
            self::getUrl($_menu, $row);
        }
        return $_menu;
    }

    static private $request = '';
    static private $requestOpt = '';

    static public function setRequest($request, $requestOpt=null) {
        self::$request = $request;
        if($requestOpt) {
            self::$requestOpt = $requestOpt;
        }
    }

    static public function getRequest() {
        if (self::$request == 'arenda' or self::$request == '')
            return 'arenda';
        else
            return 'sale';
    }
    static public function requestUrl() {
        if (self::$request == 'arenda')
            return cmfGetUrl('/arenda/request/') . (self::$requestOpt ? '?'.http_build_query(self::$requestOpt) : '');
        else
            return cmfGetUrl('/sale/request/') . (self::$requestOpt ? '?'.http_build_query(self::$requestOpt) : '');
    }

    static private function getUrlRigth(&$_menu, $row) {
        static $last = false;
        $id = $row['menu'];
        $name = $row['name'];
        $url = $row['url'];
//        list($index, $level, $class, $id, $name, $url) = $row;
        $res = cmfMenuConfig::getUrlRigth($id, $row['id']);
        if ($id === 'adress') {
            $id .= $row['id'];
        }
        if ($res) {
            if (isset($res[$id])) {
                foreach ($res as $k => $v) {
                    //$v['level'] += $level;
                    $_menu[$id . $k] = $v;
                }
            } else {
                if (!empty($name)) {
                    $res['name'] = $name;
                    $res['title'] = cmfString::specialchars($name);
                } elseif (empty($res['title'])) {
                    $res['title'] = cmfString::specialchars($res['name']);
                }
                $res['name'] = str_replace(' ', '&nbsp;', $res['name']);
                if (!empty($url))
                    $res['url'] = $url;
                $res['class'] = $row['class'];

                if ($row['level'] == 2) {

                } else {
                    $_menu[$id] = $res;
                    $last = $id;
                }
            }
        }
    }


    static private function getUrl(&$_menu, $row) {
        static $last = false;
        $id = $row['menu'];
        $name = $row['name'];
        $url = $row['url'];
//        list($index, $level, $class, $id, $name, $url) = $row;
        $res = cmfMenuConfig::getUrl($id, $row['id']);
        if ($id === 'adress') {
            $id .= $row['id'];
        }
        if ($res) {
            if (isset($res[$id])) {
                foreach ($res as $k => $v) {
                    //$v['level'] += $level;
                    $_menu[$id . $k] = $v;
                }
            } else {
                if (!empty($name)) {
                    $res['name'] = $name;
                    $res['title'] = cmfString::specialchars($name);
                } elseif (empty($res['title'])) {
                    $res['title'] = cmfString::specialchars($res['name']);
                }
                $res['name'] = str_replace(' ', '&nbsp;', $res['name']);
                if (!empty($url))
                    $res['url'] = $url;
                $res['class'] = $row['class'];

                if ($row['level'] == 2) {
                    $_menu[$last]['childs'][] = $res;
                } else {
                    $_menu[$id] = $res;
                    $last = $id;
                }
            }
        }
    }


    static private $h1 = false;
    static public function setH1($h1) {
        self::$h1 = $h1;
    }

    static public function isH1() {
        return (bool)self::$h1;
    }
    static public function h1() {
        return self::$h1;
    }

    static private $rMenu = false;
    static public function setRMenu($rMenu, $mBMenu=null) {
        self::$rMenu = array($rMenu, $mBMenu);
    }
    static public function getRMenu() {
        return get(self::$rMenu, 0);
    }
    static public function getBMenu() {
        return get(self::$rMenu, 1);
    }

    public static function getAll() {
        return array(self::$menu, self::$select, self::$request, self::$h1, self::$rMenu);
    }

    public static function setAll($v) {
        list(self::$menu, self::$select, self::$request, self::$h1, self::$rMenu) = $v;
    }

}

?>