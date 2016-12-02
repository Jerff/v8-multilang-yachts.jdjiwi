<?php

cmfLoad('packer.php/class.JavaScriptPacker');

class cmfCompileFile {

    private $include = null;

    private function reset() {
        $this->include = array();
    }

    private function compilePack($list, $file, $js) {
        if (isset($this->include[$file]))
            return '';
        foreach ($list as $dir) {
            if (is_file($dir . $file)) {
                $sourse = cmfString::convertEncoding(file_get_contents($dir . $file));
                break;
            }
        }
        if (!isset($sourse))
            return '';

        $include = '';
        preg_match_all('~//include\((.+)\);~', $sourse, $search);
        if ($search[1]) {
            foreach ($search[1] as $v)
                if (!isset($this->include[$v])) {
                    $include .= $this->compilePack($list, $v, $js);
                }
        }
//        pre($file);
        $this->include[$file] = 1;

        if (mb_strrpos($file, 'min') === false and mb_strrpos($file, 'pack') === false and $js) {
            $sourse = new JavaScriptPacker($sourse, 0);
            $sourse = $sourse->pack();
        }
        return $include . "\n" . $sourse;
    }

    public function compile($name, $js = true) {
        $sep = $js ? ';' : '';
        $sourse = '';
        list($name, $list) = $name;
        $this->reset();
        foreach ($list as $dir) {
            self::comileCssSet(str_replace(cmfWWW, '/', $dir));
            $prefix = preg_replace('~^.+(\..+)$~i', '$1', $name);
            foreach (cmfDir::getList($dir) as $file) {
                if ($name != $file and mb_strrpos($file, $prefix) !== false) {
//                    pre($file);
                    $sourse .= $this->compilePack($list, $file, $js);
                    if(!$js) {
                        $sourse = preg_replace_callback("#(url\s*\([\s\"\']*)([^\)\"\'\s]+)([\s\"\']*\))#sS", array('cmfCompileFile', 'comileCss'), $sourse);
                    }
                    $sourse .= "\n{$sep}\n";
                }
            }
        }

        if ($sourse) {
            file_put_contents(cmfUpdateCompileConfig::soursePath() . $name, $sourse);
        }
    }

    static private $value = null;

    static private function comileCssSet($value) {
        self::$value = $value;
    }

    static public function comileCssGet() {
        return self::$value;
    }

    static public function comileCss($m) {
        if (substr($m[2], 0, 3) === '../') {
            return $m[1] . self::comileCssGet() . substr($m[2], 3) . $m[3];
        } else if (substr($m[2], 0, 1) !== '/') {
            return $m[1] . self::comileCssGet() . $m[2] . $m[3];
        }
        return $m[0];
    }

}

?>
