<?php

cmfLoad('system/string');

class cmfString {

    static public function arrayToPath($arg) {
        return $arg ? '[' . implode('][', $arg) . ']' : '';
    }

    static public function pathToArray($str) {
        if (!empty($str)) {
            $str = $str ? explode('][', substr($str, 1, -1)) : array();
            return array_combine($str, $str);
        } else {
            return array();
        }
    }

    static public function unserialize($arg) {
        return $arg ? unserialize($arg) : array();
    }

    static public function serialize($arg) {
        return empty($arg) ? '' : serialize($arg);
    }

    /* translate */

    static public function translate($str) {
        static $t = array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'ж' => 'gh', 'з' => 'z', 'и' => 'i',
    'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f',
    'х' => 'x', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'th', 'ъ' => '', 'ь' => '', 'ы' => 'y', 'э' => 'eh', 'ю' => 'ju', 'я' => 'ja');

        $new = '';
        $str = mb_strtolower(trim($str), cmfCharset);
        $str = preg_replace('~\s~', '_', $str);
        for ($i = 0, $c = iconv_strlen($str, cmfCharset); $i < $c; $i++) {
            $s = iconv_substr($str, $i, 1, cmfCharset);
            if (isset($t[$s]))
                $new .= $t[$s];
            else if ((ord($s) > 126) or (ord($s) == 20))
                $new .= '_';
            else
                $new .= $s;
        }
        return $new;
    }

    /* specialchars */

    static public function specialchars($str) {
        return trim(htmlspecialchars($str, ENT_QUOTES, cmfCharset));
    }

    static public function convertEncoding($str) {
        $char = mb_detect_encoding($str);
        if ($char !== cmfCharset) {
            $str = mb_convert_encoding($str, cmfCharset);
        }
        return $str;
    }

}

?>