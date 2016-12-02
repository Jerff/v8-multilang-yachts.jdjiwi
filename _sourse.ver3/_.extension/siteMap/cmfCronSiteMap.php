<?php

cmfLoad('catalog/function');
cmfLoad('cron/cmfCronUpdateDriver');

class cmfCronSiteMap extends cmfCronUpdateDriver {

    static protected function file() {
        return 'sitemap.xml';
    }

    static private function getFile() {
        return cmfWWW . self::file();
    }

    static public function getUrlFile() {
        return cmfProjectMain . self::file();
    }

    static public function run() {
        $sql = cmfRegister::getSql();
        $res = $sql->placeholder("SELECT id, name, changefreq, priority FROM ?t WHERE visible='yes'", db_seo_sitemap)
                ->fetchAssocAll('id');

        $file = fopen(self::getFile(), 'w');
        fwrite($file, '<?xml version="1.0" encoding="UTF-8"?>');
        fwrite($file, '
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
        foreach ($res as $id => $row) {
            cmfCronRun::run();
            $changefreq = $row['changefreq'];
            $priority = $row['priority'];
            $date = date('Y-m-d');

            $_url = self::getUrlList($row['name']);
            foreach ((array) $_url as $url) if(!empty($url)) {
                $url = cmfString::specialchars($url);
                $html = <<<HTML

		   <url>
		      <loc>$url</loc>
		      <lastmod>$date</lastmod>
		      <changefreq>$changefreq</changefreq>
		      <priority>$priority</priority>
		   </url>
HTML;
                fwrite($file, $html);
            }
        }
        fwrite($file, '
		</urlset>');
        fclose($file);
    }

    static public function getUrlList($modul) {
        $sql = cmfRegister::getSql();
        switch ($modul) {
            case 'index':
                $url = array();
                self::getUr($url, '/index/');
                return $url;

//            case 'contact':
//                $url = array();
//                self::getUr($url, '/contact/');
//                return $url;

            case 'news & article':
                $res = $sql->placeholder("SELECT uri FROM ?t WHERE visible='yes'", db_news)
                        ->fetchAssocAll();
                $url = array();
                foreach ($res as $row) {
                     self::getUr($url, '/news/item/', array($row['uri']));
                }

                $res = $sql->placeholder("SELECT uri FROM ?t WHERE visible='yes'", db_article)
                        ->fetchAssocAll();
                foreach ($res as $row) {
                    self::getUr($url, '/articles/item/', array($row['uri']));
                }
                return $url;

            case 'foto & wallpapers':
                $url = array();
//                self::getUr($url, '/photo/');
//                self::getUr($url, '/wallpapers/');
                return $url;

            case 'arenda':
                $url = array();
//                self::getUr($url, '/arenda/info/');
//                self::getUr($url, '/charter/');
                $res = $sql->placeholder("SELECT t.uri AS tUri, y.uri FROM ?t y LEFT JOIN ?t t ON(y.type=t.id) WHERE t.visible='yes' AND y.visible='yes' ORDER BY y.name", db_arenda_yachts, db_arenda_type)
                        ->fetchAssocAll();
                foreach ($res as $row) {
                    self::getUr($url, '/arenda/yachts/', array($row['tUri'], $row['uri']));
                }
                $res = $sql->placeholder("SELECT isUri, `type` FROM ?t WHERE isVisible='yes' ORDER BY pos", db_arenda)
                        ->fetchAssocAll();
                foreach ($res as $row) {
                    self::getUr($url, '/arenda/', array($row['isUri']));
                    if ($row['type'] == 'yachts') {
                        self::getUr($url, '/arenda/all/', array($row['isUri']));
                    }
                }
                return $url;

            case 'brokerage':
                $url = array();
//                self::getUr($url, '/brokerage/');
                $res = $sql->placeholder("SELECT uri FROM ?t WHERE visible='yes' ORDER BY pos", db_brokerage_type)
                        ->fetchAssocAll();
                foreach ($res as $row) {
                    self::getUr($url, '/brokerage/type/', array($row['uri']));
                }
                $res = $sql->placeholder("SELECT t.uri AS tUri, .y.uri FROM ?t y LEFT JOIN ?t t ON(y.type=t.id) WHERE t.visible='yes' AND y.visible='yes' ORDER BY y.name", db_brokerage_yachts, db_brokerage_type)
                        ->fetchAssocAll();
                foreach ($res as $row) {
                    self::getUr($url, '/brokerage/yachts/', array($row['tUri'], $row['uri']));
                }
                return $url;

            case 'sale/new':
                $url = array();
//                self::getUr($url, '/sale/new/');
                $res = $sql->placeholder("SELECT uri FROM ?t WHERE visible='yes' ORDER BY pos", db_shipyards_type)
                        ->fetchAssocAll();
                foreach ($res as $row) {
                    self::getUr($url, '/sale/new/type/', array($row['uri']));
                }
                return $url;

            case 'shipyards':
                $url = array();
//                self::getUr($url, '/shipyards/');
                $res = $sql->placeholder("SELECT uri FROM ?t WHERE visible='yes' ORDER BY `main`, pos", db_shipyards)
                        ->fetchAssocAll();
                foreach ($res as $k => $row) {
                    self::getUr($url, '/shipyards/one/', array($row['uri']));
                }

                $res = $sql->placeholder("SELECT s.uri AS sUri, .y.uri FROM ?t y LEFT JOIN ?t s ON(y.shipyards=s.id) WHERE s.visible='yes' AND y.visible='yes' ORDER BY y.name", db_shipyards_yachts, db_shipyards)
                        ->fetchAssocAll();
                foreach ($res as $row) {
                    self::getUr($url, '/shipyards/yachts/', array($row['sUri'], $row['uri']));
                }
                return $url;

            case 'info':
                $res = $sql->placeholder("SELECT isUri FROM ?t WHERE isVisible='yes'", db_menu_info)
                        ->fetchAssocAll();
                $url = array();
                foreach ($res as $row) {
                    self::getUr($url, '/info/', array($row['isUri']));
                }

                $res = $sql->placeholder("SELECT isUri FROM ?t WHERE isVisible='yes'", db_content)
                        ->fetchAssocAll();
                foreach ($res as $row) {
                    self::getUr($url, '/content/', array($row['isUri']));
                }
                return $url;
        }
        return array();
    }

    static private function getUr(&$url, $page, $param = null) {
        foreach (cmfLang::getLangList() as $k => $v) {
            $url[] = cmfGetLangUrl($k, $page, $param);
        }
    }

}

?>