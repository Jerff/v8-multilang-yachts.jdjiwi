<?php

class cmfTemplateMain {

    private $teplates = null;
    private $values = array();
    private $isCacheMain = true;

    public function __construct() {
        $this->setTeplates('main.index.php');
    }

    public function setTeplates($t) {
        $this->teplates = $t;
    }

    public function getTeplates() {
        return $this->teplates;
    }

    private function resetCacheMain() {
        $this->isCacheMain = true;
    }

    private function setCacheMain($c) {
        $this->isCacheMain &= $c;
    }

    private function getCacheMain() {
        return $this->isCacheMain;
    }

    public function main() {
        $this->resetCacheMain();

        $page = cmfPages::getMain();
        $conf = cmfPages::getPage($page);

        if (cmfCache::isNoPages())
            $cache = false;
        else
            $cache = !isset($conf['!cache']);

        if ($cache) {
            cmfCacheSite::readMainPage(cmfCacheSite::getFileMain($page));
            $c = $this->mainRun($page);
            if ($this->getCacheMain()) {
                cmfCacheSite::savePage(cmfCacheSite::getFileMain($page), $c);
            }
            return $c;
        } else {
            return $this->mainRun($page);
        }
    }

    private function mainRun($page) {
        $t = cmfPages::getPageFlag($page, 't');
        $this->setTeplates(cmfPages::getTemplates($t));
        $content = $this->run($page);

        if (is_int($t)) {
            ob_start();
            $content = include cmfTeplates . $this->getTeplates();
            $content = ob_get_clean();
        }
        return $content;
    }

    private function &runAll() {
        $m = array();
        foreach (func_get_args() as $p)
            $m[] = $this->run($p);
        return $m;
    }

    public function run($page) {
        $conf = cmfPages::getPage($page);
        if (is_null($conf))
            return false;

        cmfPages::setItem($page);

        if (cmfCache::isNoPages())
            $cache = false;
        else
            $cache = !isset($conf['!cache']);
        $this->setCacheMain($cache);


        if ($cache) {
            if (isset($conf['noUrl'])) {
                if (isset($conf['isMain'])) {
                    $file = cmfCacheSite::getFilePageOfMain(cmfPages::getMain(), $page);
                } else {
                    $file = cmfCacheSite::getFilePage($page);
                }
            } else {
                $url = isset($conf['Request']) ? cmfPages::getUri() : cmfPages::getPath();
                if (isset($conf['isMain'])) {
                    $file = cmfCacheSite::getFilePageUrlOfMain(cmfPages::getMain(), $page, $url);
                } else {
                    $file = cmfCacheSite::getFilePageUrl($page, $url);
                }
            }
            if (!$c = cmfCacheSite::readPage($this, $file)) {
                $c = $this->runPage($conf['path']);
                cmfCacheSite::savePage($this, $file, $c);
            }
            return $c;
        }

        return $this->runPage($conf['path']);
    }

    private function runPage($path) {
        ob_start();
        $r = $this->processingPhp($path);

        if ($r !== 1) {
            if ($r === 404) {
                header("HTTP/1.0 404 Not Found");
            }
            if ($r === 404 or $r === 403) {
                $r = '/' . $r . '/';
            }
            cmfPages::setMain($r);
            echo $this->main();
            exit;
        }

        $this->processingPhtml($path);

        return ob_get_clean();
    }

    public function assing($name, &$value) {
        $this->values[$name] = $value;
    }

    public function assing2($name, $value) {
        $this->values[$name] = $value;
    }

    private function &getAssing() {
        $v = $this->values;
        $this->values = array();
        return $v;
    }

    private function processingPhp($p58) {

        if (cmfComplile < 2) {
            return require(cmfModel . $p58 . '.php');
        }

        $file = cmfCompilePath . urlencode($p58) . '.php';
        if (file_exists($file)) {
            if (cmfComplile == 3)
                return require($file);
            else {
                if (filemtime(cmfModel . $p58 . '.php') < filemtime($file))
                    return require($file);
            }
        }
        $compile = new cmfCompileFile();
        file_put_contents($file, $compile->compile(cmfModel . $p58 . '.php', true));
        unset($compile, $p58);

        return require($file);
    }

    private function processingPhtml($p58) {
        extract($this->getAssing());
//        if(is_file(cmfView . $p58 . '.modile.phtml')) {
//            require(cmfView . $p58 . '.modile.phtml');
//        } else {
            require(cmfView . $p58 . '.phtml');
//        }
    }

}

?>