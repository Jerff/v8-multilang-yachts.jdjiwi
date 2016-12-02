<?php

class cmfMenuConfig {

    static public function _getUrl($id, $index = 0) {
        switch ($id) {
            case 'index':
                return array('name' => 'Главная',
                    'url' => cmfGetUrl('/index/'));


            case 'news':
                return array('name' => 'Новости',
                    'level' => 0,
                    'url' => cmfGetUrl('/news/'));
            case 'article':
                return array('name' => 'Статьи и Обзоры',
                    'level' => 0,
                    'url' => cmfGetUrl('/article/'));


            // foto
            case 'foto':
                return array('name' => 'Фотографии',
                    'level' => 0,
                    'url' => cmfGetUrl('/foto/'));
            case 'wallpapers':
                return array('name' => 'Обои',
                    'level' => 0,
                    'url' => cmfGetUrl('/wallpapers/'));


            // sale
            case 'sale/new':
                return array('name' => 'Каталог новых яхт',
                    'level' => 0,
                    'url' => cmfGetUrl('/sale/new/'));
            case 'sale/new/search':
                return array('name' => 'Подбор яхты',
                    'level' => 0,
                    'url' => cmfGetUrl('/sale/new/search/'));
            case 'brokerage':
                return array('name' => 'Брокераж',
                    'level' => 0,
                    'url' => cmfGetUrl('/brokerage/'));
            case 'request/arenda':
                return array('name' => 'Запрос на подбор яхты',
                    'level' => 0,
                    'url' => cmfGetUrl('/request/arenda/'));
            case 'request/sale':
                return array('name' => 'Запрос на подбор яхты',
                    'level' => 0,
                    'url' => cmfGetUrl('/request/sale/'));
            case 'shipyards':
                return array('name' => 'Все верфи мира',
                    'level' => 0,
                    'url' => cmfGetUrl('/shipyards/'));

            case 'map':
                return array('name' => 'Карта сайта',
                    'level' => 0,
                    'url' => cmfGetUrl('/map/'));
            case 'contact':
                return array('name' => 'Контакты',
                    'level' => 0,
                    'url' => cmfGetUrl('/contact/'));

            case 'adress':
                return array('name' => '',
                    'level' => 0,
                    'url' => '');

            case 'arenda':
                return array('name' => 'Аренда',
                    'level' => 0,
                    'url' => cmfGetUrl('/arenda/info/'));

            case 'arenda/charter':
                $menu = array();
                $menu['arenda/charter'] = array('name' => 'Международный чартер',
                    'level' => 0,
                    'url' => cmfGetUrl('/charter/'));
                /* $tmp2 = cmfRegister::getSql()->placeholder("SELECT id, level, name, isUri as url FROM ?t WHERE parent='0' AND isVisible='yes' ORDER BY pos", db_arenda)
                  ->fetchAssocAll('id');
                  foreach($tmp2 as $k=>$row) {
                  $menu[$k] = array('name'=>$row['name'],
                  'level'=>1,
                  'url'=>cmfGetUrl('/arenda/', array($row['url'])));

                  } */
                return $menu;
        }
        return null;
    }

    static public function getUrl($id, $index = 0) {
        $menu = self::_getUrl($id, $index);
        if (!is_null($menu))
            return $menu;
        switch ($id) {

            default:
                $id2 = (int) $id;
                static $tmp1, $tmp2, $menuPages;

                switch (str_replace($id2, '', $id)) {
                    case 'menu':
                        if (!$tmp1) {
                            $tmp1 = cmfRegister::getSql()->placeholder("SELECT parent, pages, id, isUri as url FROM ?t WHERE id NOT IN('32') AND isVisible='yes' ORDER BY pos", db_menu_info)
                                    ->fetchAssocAll('parent', 'id');
                            $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name, menu FROM ?t", db_menu_info_lang)
                                    ->fetchAssocAll('id', 'lang');
                            foreach ($tmp1 as $p => $res) {
                                foreach ($res as $k => $row) {
                                    cmfLang::data($row, get($list, $k));
//                                    if($row['url']==cmfMenu::searchUri) continue;
                                    $tmp1[$p][$k] = array(
                                        'name' => empty($row['menu']) ? $row['name'] : $row['menu'],
                                        'pages' => $row['pages'],
                                        'title' => cmfString::specialchars(empty($row['menu']) ? $row['name'] : $row['menu']),
                                        'url' => cmfGetUrl('/info/', array($row['url']))
                                    );
                                }
                            }
                            foreach ($tmp1 as $p => $res) {
                                foreach ($res as $k => $row) {
                                    if (!empty($row['pages'])) {
                                        if(!isset($menuPages[$row['pages']])) {
                                            $menuPages[$row['pages']] = cmfMenu::getRigth($row['pages']);
                                        }
                                        if (!empty($menuPages[$row['pages']])) {
                                            $tmp1[$k] = $menuPages[$row['pages']];
                                        } else {
                                            switch ($row['pages']) {
                                                case 'arenda-ukraine':
                                                    $res = cmfRegister::getSql()->placeholder("SELECT id, isUri FROM ?t WHERE parent=? AND isVisible='yes' ORDER BY level, pos DESC", db_arenda, arendaMenu)
                                                            ->fetchAssocAll('id');
                                                    $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_arenda_lang, array_keys($res))
                                                            ->fetchAssocAll('id', 'lang');
                                                    foreach ($res as $j => $row) {
                                                        cmfLang::data($row, get($list, $j));
                                                        $new = array(
                                                            'name' => $row['name'],
                                                            'title' => cmfString::specialchars($row['name']),
                                                            'url' => cmfGetUrl('/arenda/', array($row['isUri']))
                                                        );
                                                        array_unshift($tmp1[$k], $new);
                                                    }
                                                    break;

                                                case 'charter':
                                                    $res = cmfRegister::getSql()->placeholder("SELECT id, isUri FROM ?t WHERE parent='0' AND isVisible='yes' ORDER BY level, pos", db_arenda)
                                                            ->fetchAssocAll('id');
                                                    $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_arenda_lang, array_keys($res))
                                                            ->fetchAssocAll('id', 'lang');
                                                    foreach ($res as $j => $row) {
                                                        cmfLang::data($row, get($list, $j));
                                                        $tmp1[$k][] = array(
                                                            'name' => $row['name'],
                                                            'title' => cmfString::specialchars($row['name']),
                                                            'url' => cmfGetUrl('/arenda/', array($row['isUri']))
                                                        );
                                                    }
                                                    break;

                                                default:
                                                    break;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if (isset($tmp1[0][$id2])) {
                            $tmp1[0][$id2]['childs'] = get($tmp1, $id2);
                            return $tmp1[0][$id2];
                        }
                        break;

                    case 'content':
                        if (!$tmp2) {
                            $tmp2 = cmfRegister::getSql()->placeholder("SELECT id, level, isUri as url FROM ?t WHERE isVisible='yes'", db_content)
                                    ->fetchAssocAll('id');
                            $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_content_lang, array_keys($tmp2))
                                    ->fetchAssocAll('id', 'lang');
                            foreach ($tmp2 as $k => $row) {
                                cmfLang::data($row, get($list, $k));
                                $tmp2[$k] = array(
                                    'name' => $row['name'],
                                    'title' => cmfString::specialchars($row['name']),
                                    'url' => cmfGetUrl('/content/', array($row['url']))
                                );
                            }
                        }
                        if (isset($tmp2[$id2]))
                            return $tmp2[$id2];
                        break;

                    case 'arenda':

                        $res = cmfRegister::getSql()->placeholder("SELECT id, level, name, isUri as url FROM ?t WHERE (id=? OR path LIKE '%[?i]%') AND isVisible='yes' ORDER BY level, pos", db_arenda, $id2, $id2)
                                ->fetchAssocAll();
                        $menu = array();
                        $level = 0;
                        foreach ($res as $k => $row) {
                            if (!$level) {
                                $level = $row['level'];
                                $row['id'] = $id;
                            } else {
                                if (($row['level'] - $level) != 1) {
                                    continue;
                                }
                            }
                            $menu[$row['id']] = array(
                                'name' => $row['name'],
                                'title' => cmfString::specialchars($row['name']),
                                'url' => cmfGetUrl('/arenda/', array($row['url']))
                            );
                        }
                        return $menu;
                }
        }
    }

    static public function getUrlRigth($id, $index = 0) {
        $menu = self::_getUrl($id, $index);
        if (!is_null($menu))
            return $menu;
        switch ($id) {

            default:
                $id2 = (int) $id;
                static $tmp1, $tmp2;

                switch (str_replace($id2, '', $id)) {
                    case 'menu':
                        if (!$tmp1) {
                            $tmp1 = cmfRegister::getSql()->placeholder("SELECT  pages, id, isUri as url FROM ?t WHERE isVisible='yes' ORDER BY pos", db_menu_info)
                                    ->fetchAssocAll('id');
                            $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name, menu FROM ?t", db_menu_info_lang)
                                    ->fetchAssocAll('id', 'lang');
                            foreach ($tmp1 as $k => $row) {
                                cmfLang::data($row, get($list, $k));
                                $tmp1[$k] = array(
                                    'name' => empty($row['menu']) ? $row['name'] : $row['menu'],
                                    'pages' => $row['pages'],
                                    'title' => cmfString::specialchars(empty($row['menu']) ? $row['name'] : $row['menu']),
                                    'url' => cmfGetUrl('/info/', array($row['url']))
                                );
                            }
                        }

                        if (isset($tmp1[$id2])) {
                            return $tmp1[$id2];
                        }
                        break;

                    case 'content':
                        if (!$tmp2) {
                            $tmp2 = cmfRegister::getSql()->placeholder("SELECT id, level, isUri as url FROM ?t WHERE isVisible='yes'", db_content)
                                    ->fetchAssocAll('id');
                            $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_content_lang, array_keys($tmp2))
                                    ->fetchAssocAll('id', 'lang');
                            foreach ($tmp2 as $k => $row) {
                                cmfLang::data($row, get($list, $k));
                                $tmp2[$k] = array(
                                    'name' => $row['name'],
                                    'title' => cmfString::specialchars($row['name']),
                                    'url' => cmfGetUrl('/content/', array($row['url']))
                                );
                            }
                        }
                        if (isset($tmp2[$id2]))
                            return $tmp2[$id2];
                        break;

                    case 'arenda':

                        $res = cmfRegister::getSql()->placeholder("SELECT id, level, name, isUri as url FROM ?t WHERE (id=? OR path LIKE '%[?i]%') AND isVisible='yes' ORDER BY level, pos", db_arenda, $id2, $id2)
                                ->fetchAssocAll();
                        $menu = array();
                        $level = 0;
                        foreach ($res as $k => $row) {
                            if (!$level) {
                                $level = $row['level'];
                                $row['id'] = $id;
                            } else {
                                if (($row['level'] - $level) != 1) {
                                    continue;
                                }
                            }
                            $menu[$row['id']] = array(
                                'name' => $row['name'],
                                'title' => cmfString::specialchars($row['name']),
                                'url' => cmfGetUrl('/arenda/', array($row['url']))
                            );
                        }
                        return $menu;
                }
        }
    }

}

?>