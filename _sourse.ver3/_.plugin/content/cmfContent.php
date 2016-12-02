<?php

class cmfContent {

    const width = 660;
    const height = 296;
    const yachtWidth = 590;
    const yachtHeight = 266;

    static private $template = false;
    static private $pages = false;

    static public function pareseTel($tel) {
        return str_replace(array(' ', '(', ')', '-'), '', $tel);
    }

    static public function replace(&$template, &$content, $mSlider = array(), $isYacht = false) {
        self::setTemplate($template);
        self::slider($content, $mSlider, $isYacht);
        preg_match_all('~\%([\.a-z]+)\(([\.\,a-z0-9 ]*)\)\%~iS', $content, $tmp);
        foreach ($tmp[1] as $k => $func) {
            $func = 'call' . str_replace(' ', '', ucwords(str_replace('.', ' ', $func)));
            if (method_exists('cmfContent', $func)) {
//                $res = call_user_func_array('static::' . $func, empty($tmp[2][$k]) ? array() : array_map('trim', explode(',', $tmp[2][$k])));
                $res = call_user_func_array(array('cmfContent', $func), empty($tmp[2][$k]) ? array() : array_map('trim', explode(',', $tmp[2][$k])));
                $content = str_replace('<p>' . $tmp[0][$k] . '</p>', $tmp[0][$k], $content);
                $content = str_replace($tmp[0][$k], $res, $content);
            }
        }
//        pre(self::type());
        switch (self::type()) {
            case 'news':
                $content .= self::template()->run('/news/');
                break;
            case 'blog':
                $content .= self::template()->run('/blog/');
                break;
            case 'articles':
                $content .= self::template()->run('/articles/');
                break;
            case 'board':
//                $content .= self::template()->run('/board/');
                cmfGlobal::set('header-content', self::template()->run('/board/'));
                break;
            case 'photo':
                $content = self::template()->run('/photo/') . $content;
                break;
            case 'wallpapers':
                $content = self::template()->run('/wallpapers/') . $content;
                break;
            case 'selfie':
                $content = $content . self::template()->run('/selfie/');
                break;

            case 'brokerage':
                cmfGlobal::set('header-content', self::template()->run('/brokerage/'));
                break;
            case 'new_sale':
                cmfGlobal::set('header-content', self::template()->run('/sale/new/'));
                break;
            case 'shipyards':
                cmfGlobal::set('header-content', self::template()->run('/shipyards/'));
//                $content = self::template()->run('/shipyards/') . $content;
                break;

            case 'form.sale':
                $content .= self::template()->run('/form/sale/');
                break;
            case 'form.arenda':
                $content .= self::template()->run('/form/arenda/');
                break;

            case 'contact':
                cmfGlobal::set('body', 'contacts');
                break;

            case 'searchYachts':
                cmfGlobal::set('body', 'rent');
                cmfGlobal::set('searchYachts', 1);
                self::template()->setTeplates('main.yachts.php');
                break;

            default :
                break;
        }
    }

    static public function getStatic($infoId) {
        $info = cmfRegister::getSql()->placeholder("SELECT id FROM ?t WHERE name=?", db_content_static, $infoId)
                ->fetchAssoc('id');
        $list = cmfRegister::getSql()->placeholder("SELECT lang, content FROM ?t WHERE id=?", db_content_static_lang, $info['id'])
                ->fetchAssocAll('lang');
        cmfLang::data($info, $list);
        return $info['content'];
    }

    static public function info($infoId, $isPath = true) {
        $sql = cmfRegister::getSql();
        $where = is_numeric($infoId) ? array('id' => $infoId) : array('pages' => $infoId);
        $info = $sql->placeholder("SELECT i.id, i.path, i.parent, i.pages, i.request, i.isUri FROM ?t i WHERE ?w:i AND i.isVisible='yes'", db_menu_info, $where)
                ->fetchAssoc();
        if (!$info)
            return false;
        $list = $sql->placeholder("SELECT lang, name, menu, content, title, keywords, description FROM ?t WHERE id=?", db_menu_info_lang, $info['id'])
                ->fetchAssocAll('lang');
        cmfLang::data($info, $list);

        $headerId = ($infoId == arendaId ? arendaInfo : $infoId) . 'menu';
        $request = get($info, 'request');
        if ($infoId == boardId)
            $info['isBoard'] = true;


        $mPath = array();
        if (!empty($info['path'])) {
            $res = $sql->placeholder("SELECT id, parent, request, isUri FROM ?t WHERE id ?@ AND visible='yes' ORDER BY level", db_menu_info, cmfString::pathToArray($info['path']))
                    ->fetchAssocAll('id');
            $list = $sql->placeholder("SELECT id, lang, name, menu FROM ?t WHERE id ?@", db_menu_info_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            foreach ($res as $id => $row) {
                cmfLang::data($row, get($list, $id));
                if ($id == boardId)
                    $info['isBoard'] = true;
                if (empty($row['parent'])) {
                    $headerId = $id . 'menu';
                }
                if (!empty($row['request']) and empty($request)) {
                    $request = $row['request'];
                }
                $mPath[cmfGetUrl('/info/', array($row['isUri']))] = empty($row['menu']) ? $row['name'] : $row['menu'];
//                cmfMenu::add(empty($row['menu']) ? $row['name'] : $row['menu'], cmfGetUrl('/info/', array($row['isUri'])));
            }
        }
        if ($isPath) {
            $mPath[cmfGetUrl('/info/', array($info['isUri']))] = empty($info['menu']) ? $info['name'] : $info['menu'];
        } else {
            $mPath[] = empty($info['menu']) ? $info['name'] : $info['menu'];
        }

        return array($info, $request, $headerId, $mPath);
    }

    static public function initSubMenu($info, $infoId, $arendaId = 0, $boardId = 0) {
        $mMenu = array();
        if ($arendaId) {
            $res = cmfRegister::getSql()->placeholder("SELECT id, parent, isUri FROM ?t WHERE parent=? AND isVisible='yes' ORDER BY level, pos", db_arenda, $arendaId)
                    ->fetchAssocAll('id');
            if (!$res) {
                $res = cmfRegister::getSql()->placeholder("SELECT id, parent, isUri FROM ?t WHERE parent=(SELECT parent FROM ?t WHERE id=?) AND isVisible='yes' ORDER BY level, pos", db_arenda, db_arenda, $arendaId)
                        ->fetchAssocAll('id');
            }

            $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_arenda_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            foreach ($res as $j => $row) {
                cmfLang::data($row, get($list, $j));
                if ($row['parent'] == arendaMenu) {
                    $isUkraineMenu = true;
                }
                $mMenu[] = array(
                    'name' => $row['name'],
                    'title' => cmfString::specialchars($row['name']),
                    'url' => cmfGetUrl('/arenda/', array($row['isUri']))
                );
            }

            if (isset($isUkraineMenu)) {
                $res = cmfRegister::getSql()->placeholder("SELECT id, isUri FROM ?t WHERE parent=(SELECT id FROM ?t WHERE pages='arenda-ukraine') AND isVisible='yes' ORDER BY level, pos", db_menu_info, db_menu_info, arendaMenu)
                        ->fetchAssocAll('id');
                $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name, menu FROM ?t WHERE id ?@", db_menu_info_lang, array_keys($res))
                        ->fetchAssocAll('id', 'lang');
                foreach ($res as $id => $row) {
                    cmfLang::data($row, get($list, $id));
                    $mMenu[$id] = array(
                        'name' => empty($row['menu']) ? $row['name'] : $row['menu'],
                        'title' => htmlspecialchars(empty($row['menu']) ? $row['name'] : $row['menu']),
                        'url' => cmfGetUrl('/info/', array($row['isUri']))
                    );
                }
            }
            return $mMenu;
        }

        if (!empty($info['pages'])) {
            $res = cmfMenu::getRigth($info['pages']);
//            pre($res, $mMenu);
//
//            exit;
            if (!empty($res)) {
                foreach ($res as $key => $value) {
                    $mMenu[] = $value;
                }
                return $mMenu;
            } else {
                switch ($info['pages']) {
                    case 'arenda':
                        $infoId = arendaInfo;
                    case 'arenda-ukraine':
                        $res = cmfRegister::getSql()->placeholder("SELECT id, isUri FROM ?t WHERE parent=? AND isVisible='yes' ORDER BY level, pos", db_arenda, arendaMenu)
                                ->fetchAssocAll('id');
                        $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_arenda_lang, array_keys($res))
                                ->fetchAssocAll('id', 'lang');
                        foreach ($res as $j => $row) {
                            cmfLang::data($row, get($list, $j));
                            $mMenu[] = array(
                                'name' => $row['name'],
                                'title' => cmfString::specialchars($row['name']),
                                'url' => cmfGetUrl('/arenda/', array($row['isUri']))
                            );
                        }
                        break;

                    case 'charter':
                        $res = cmfRegister::getSql()->placeholder("SELECT id, isUri FROM ?t WHERE parent='0' AND isVisible='yes' ORDER BY level, pos", db_arenda)
                                ->fetchAssocAll('id');
                        $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name FROM ?t", db_arenda_lang)
                                ->fetchAssocAll('id', 'lang');
                        foreach ($res as $j => $row) {
                            cmfLang::data($row, get($list, $j));
                            $mMenu[] = array(
                                'name' => $row['name'],
                                'title' => cmfString::specialchars($row['name']),
                                'url' => cmfGetUrl('/arenda/', array($row['isUri']))
                            );
                        }
                        break;

                    case 'board':
                        //                    $mMenu[] = array(
                        //                        'name' => word('Добавить объявление'),
                        //                        'title' => cmfString::specialchars(word('Добавить объявление')),
                        //                        'url' => cmfGetUrl('/board/add/')
                        //                    );
                        //                    if (cmfRegister::getUser()->is()) {
                        //                        $mMenu[] = array(
                        //                            'name' => word('Личный кабинет'),
                        //                            'title' => cmfString::specialchars(word('Личный кабинет')),
                        //                            'url' => cmfGetUrl('/user/')
                        //                        );
                        //                    }
                        //                    if ($boardId) {
                        //                        $mMenu[] = array(
                        //                            'name' => word('Редактировать объявление'),
                        //                            'title' => cmfString::specialchars(word('Редактировать объявление')),
                        //                            'url' => cmfGetUrl('/board/edit/', array($boardId))
                        //                        );
                        //                    }
                        $isParentMenu = true;
                        break;

                    default:
                        break;
                }
            }
        }


        $sql = cmfRegister::getSql();
        $res = $sql->placeholder("SELECT id, pages, isUri FROM ?t WHERE parent=? AND visible='yes' ORDER BY level, pos", db_menu_info, $infoId)
                ->fetchAssocAll('id');
        if (empty($res) and ( empty($mMenu) or isset($isParentMenu)) and $info['parent']) {
            $parent = $sql->placeholder("SELECT i.id, i.path, i.parent, i.pages, i.request, i.isUri FROM ?t i WHERE i.id=? AND i.isVisible='yes'", db_menu_info, $info['parent'])
                    ->fetchAssoc();
            if (isset($info['isBoard']))
                $parent['isBoard'] = true;
            return array_merge($mMenu, self::initSubMenu($parent, $parent['id'], $arendaId, $boardId));
        }
        if ($res) {
            $list = $sql->placeholder("SELECT id, lang, name, menu FROM ?t WHERE id ?@", db_menu_info_lang, array_keys($res))
                    ->fetchAssocAll('id', 'lang');
            $last = 0;
            foreach ($res as $id => $row) {
                cmfLang::data($row, get($list, $id));
                $last = $id;
                if ($row['isUri'] == cmfMenu::searchUri)
                    continue;
                $mMenu[$id] = array(
                    'name' => empty($row['menu']) ? $row['name'] : $row['menu'],
                    'title' => htmlspecialchars(empty($row['menu']) ? $row['name'] : $row['menu']),
                    'url' => cmfGetUrl('/info/', array($row['isUri']))
                );
            }
            $mMenu[$last]['sel'] = true;
        }
        if (isset($info['isBoard'])) {
            $mMenu['isBoard'] = false;
        }
        return $mMenu;
    }

    static public function initBoardMenu($boardId = 0) {
        $mMenu = array();
//        $mMenu[] = array(
//            'name' => word('Добавить объявление'),
//            'title' => cmfString::specialchars(word('Добавить объявление')),
//            'url' => cmfGetUrl('/board/add/')
//        );
        if ($boardId) {
            $mMenu[] = array(
                'name' => word('Редактировать объявление'),
                'title' => cmfString::specialchars(word('Редактировать объявление')),
                'url' => cmfGetUrl('/board/edit/', array($boardId))
            );
        }
        if (cmfRegister::getUser()->is()) {
            $is = cmfRegister::getSql()->placeholder("SELECT 1 FROM ?t WHERE user=?", db_board, cmfRegister::getUser()->getId())
                    ->numRows();
            if ($is) {
                $mMenu[] = array(
                    'name' => word('Все объявления'),
                    'title' => cmfString::specialchars(word('Все объявления')),
                    'url' => cmfGetUrl('/user/board/')
                );
            }
            $mMenu[] = array(
                'name' => word('Личный кабинет'),
                'title' => cmfString::specialchars(word('Личный кабинет')),
                'url' => cmfGetUrl('/user/')
            );
            $mMenu[] = array(
                'name' => word('Изменить пароль'),
                'title' => cmfString::specialchars(word('Изменить пароль')),
                'url' => cmfGetUrl('/user/info/password/')
            );
            $mMenu[] = array(
                'name' => word('Выход'),
                'title' => cmfString::specialchars(word('Выход')),
                'url' => cmfGetUrl('/user/exit/')
            );
        } else {
            $mMenu[] = array(
                'name' => word('Личный кабинет'),
                'title' => cmfString::specialchars(word('Личный кабинет')),
                'url' => cmfGetUrl('/user/')
            );
        }
        return $mMenu;
    }

    static public function setType($pages) {
        self::$pages = $pages;
    }

    static protected function type() {
        return self::$pages;
    }

    static protected function setTemplate($template) {
        self::$template = $template;
    }

    static protected function template() {
        return self::$template;
    }

    static private function noticeSlider(&$notice) {
        return preg_replace('~(<p>)(<img[^>]+>)(</p>)~S', '$2', $notice);
    }

    static public function slider(&$content, $mSlider, $isYacht = false) {
        if (empty($mSlider))
            return;
        ob_start();
        $width = $isYacht ? self::yachtWidth : self::width;
        $height = $isYacht ? self::yachtHeight : self::height;
        $url = trim($_SERVER['REQUEST_URI'], "/");
        $url_name_tmp = explode('/', $url);
        $url_name = end($url_name_tmp);
        //cl($url_name);
        ///cl($mSlider,1);
        //,$row['name']
        //cl("asd",1);

        if (count($mSlider) > 1) {
            ?>
            <div id="nivoslider-wrapper" class="<?= $isYacht ? 'nivoslider-arenda' : 'nivoslider-info' ?>">
                <div id="nivoslider" class="nivoSlider">
                    <? foreach ($mSlider as $i => $row) { ?>
                        <? if (!empty($row['link'])) { ?><a href="<?= $row['link'] ?>"><? } ?>
                            <img src="<?= cmfImage::preview($row['image'], $width, $height, $url_name, $i + 1) ?>" alt="<?= $row['alt'] ?>" title="<?= $row['title'] ?>" />
                            <? if (!empty($row['link'])) { ?></a><? } ?>
                    <? } ?>
                </div>
                <?
                foreach ($mSlider as $id => $row)
                    if (!empty($row['notice'])) {
                        ?>
                        <div id="notice<?= $id ?>" class="nivo-caption">
                            <div class="comment">
                                <?= self::noticeSlider($row['notice']) ?>
                            </div>
                        </div>
                    <? } ?>
            </div>
            <?
        } else {
            foreach ($mSlider as $row) {
                ?><div class="nivoslider-info-one">
                    <? if (!empty($row['link'])) { ?><a href="<?= $row['link'] ?>"><? } ?>
                        <img src="<?= cmfImage::preview($row['image'], $width, $height) ?>" alt="<?= $row['alt'] ?>" title="<?= $row['alt'] ?>"/>
                        <? if (!empty($row['link'])) { ?></a><? } ?>
                    <? if (!empty($row['notice'])) {
                        ?>
                        <div class="nivo-caption">
                            <div class="comment">
                                <?= self::noticeSlider($row['notice']) ?>
                            </div>
                        </div>
                    <? } ?>
                </div><?
            }
        }
        if (strpos($content, '%slider%') === false) {
            $content = ob_get_clean() . $content;
        } else {
            $content = str_replace('<p>%slider%</p>', '%slider%', $content);
            $content = str_replace('%slider%', ob_get_clean(), $content);
        }
    }

    static public function indexSlider($mSlider) {
        if (empty($mSlider))
            return '';
        ob_start();
        ?>
        <div id="nivoslider-wrapper">
            <div id="nivoslider-wrapper-2">
                <div id="nivoslider" class="nivoSlider">
                    <? foreach ($mSlider as $row) { ?>
                        <? if (!empty($row['link']) and empty($row['notice'])) { ?><a href="<?= $row['link'] ?>"><? } ?>
                            <img src="<?= $row['image'] ?>" alt="<?= $row['alt'] ?>" title="<?= $row['title'] ?>" />
                            <? if (!empty($row['link']) and empty($row['notice'])) { ?></a><? } ?>
                    <? } ?>
                </div>
                <?
                foreach ($mSlider as $id => $row)
                    if (!empty($row['notice'])) {
                        ?>
                        <div id="notice<?= $id ?>" class="nivo-html-caption">
                            <div class="comment">
                                <?= self::noticeSlider($row['notice']) ?>
                            </div>
                        </div>
                    <? } ?>
            </div>
        </div>
        <?
        return ob_get_clean();
    }

    static public function indexSliderVideo($mSlider) {
        if (empty($mSlider))
            return '';
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var slider = $('#minimal-slider').advancedSlider({width: 980,
                    height: 400,
                    skin: 'minimal-small',
                    effectType: 'slide',
                    slideEasing: 'easeInOutExpo',
                    overrideTransition: true,
                    keyboardNavigation: true,
                    slideButtons: false,
                    slideArrows: false,
                    timerAnimation: false,
                    timerStrokeColor1: '#ffffff',
                    timerStrokeColor2: '#000000',
                    timerStrokeOpacity1: 0.3,
                    timerStrokeOpacity2: 0.3,
                    timerStrokeWidth1: 6,
                    timerStrokeWidth2: 3,
                    slideshowDelay: sliderTime * 1000,
                    shadow: false,
                });
                $('.slider-prev').click(function () {
                    slider.previousSlide();
                });
                $('.slider-next').click(function () {
                    slider.nextSlide();
                });
                //                $('#minimal-slider a.video').click();
                $("a[rel^='slider-prettyPhoto']").prettyPhoto();
                $("a.slider-video img").mouseenter(function () {
                    $(this).stop().animate({opacity: 0.7}, 300);
                }).mouseout(function () {
                    $(this).stop().animate({opacity: 1}, 300);
                });
            });
        </script>
        <div id="nivoslider-wrapper">
            <div id="nivoslider-wrapper-2">
                <div class="advanced-slider" id="minimal-slider">
                    <ul class="slides">
                        <? foreach ($mSlider as $row) { ?>
                            <li class="slide">
                                <? if (!empty($row['video'])) { ?>
                                    <? if (!empty($row['link'])) { ?>
                                        <a href="<?= $row['link'] ?>">
                                        <? } ?>
                                        <img class="item-slider-img" src="<?= $row['image'] ?>" alt="<?= $row['alt'] ?>" title="<?= $row['alt'] ?>" />
                                        <? if (!empty($row['link'])) { ?>
                                        </a>
                                    <? } ?>
                                    <a rel="slider-prettyPhoto" style="<?= $row['video_style'] ?>" class="slider-video" href="<?= $row['video'] ?>">
                                        <img src="<?= $row['video_image'] ?>" alt="<?= $row['alt'] ?>" title="<?= $row['alt'] ?>"/>
                                        <!--<span class="video-play-button"></span>-->
                                    </a>
                                <? } else { ?>
                                    <? if (!empty($row['link'])) { ?>
                                        <a href="<?= $row['link'] ?>">
                                        <? } ?>
                                        <img class="item-slider-img" src="<?= $row['image'] ?>" alt="<?= $row['alt'] ?>" title="<?= $row['alt'] ?>" />
                                        <? if (!empty($row['link'])) { ?>
                                        </a>
                                    <? } ?>
                                <? } ?>
                                <? if (!empty($row['notice'])) { ?>
                                    <div class="nivo-caption">
                                        <div class="comment">
                                            <?= self::noticeSlider($row['notice']) ?>
                                        </div>
                                    </div>
                                <? } ?>
                            </li>
                        <? } ?>
                    </ul>
                </div>
            </div>
            <ul class="slider-direction-nav">
                <li><a class="slider-prev">Previous</a></li>
                <li><a class="slider-next">Next</a></li>
            </ul>
        </div>
        <?
        return ob_get_clean();
    }

    static protected function callYachtsRecommend() {
        return self::template()->run('/index/yachts/');
    }

    static protected function callMap() {
        return self::template()->run('/map/');
    }

    static protected function callReviews() {
        return self::template()->run('/reviews/');
    }

    static protected function callBrand() {
        return self::template()->run('/reviews/brand/');
    }

    static protected function callFeedback() {
        return self::template()->run('/form/feedback/');
    }

    static protected function callTable($id) {
        cmfGlobal::set('tableId', $id);
        return self::template()->run('/table/one/');
    }

    static protected function callSaleSearch() {
        return ''; //self::template()->run('/menu/search/');
    }

}
?>