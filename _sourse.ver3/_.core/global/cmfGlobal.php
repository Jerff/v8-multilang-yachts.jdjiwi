<?php

class cmfGlobal {

    private static $value = array();

    public static function is($n) {
        return array_key_exists($n, self::$value);
    }

    public static function get($n, $d = null) {
        return get(self::$value, $n, $d);
    }

    public static function get2($n, $k) {
        return get2(self::$value, $n, $k);
    }

    public static function set() {
        switch (func_num_args()) {
            case 2:
                self::$value[func_get_arg(0)] = func_get_arg(1);
                break;

            case 3:
                self::$value[func_get_arg(0)][func_get_arg(1)] = func_get_arg(2);
                break;

            case 4:
                self::$value[func_get_arg(0)][func_get_arg(1)][func_get_arg(2)] = func_get_arg(3);
                break;
        }
    }

    public static function del() {
        switch (func_num_args()) {
            case 1:
                unset(self::$value[func_get_arg(0)]);
                break;

            case 2:
                unset(self::$value[func_get_arg(0)][func_get_arg(1)]);
                break;

            case 3:
                unset(self::$value[func_get_arg(0)][func_get_arg(1)][func_get_arg(2)]);
                break;
        }
    }

    public static function getAll() {
        return self::$value;
    }

    public static function setAll($v) {
        self::$value = $v;
    }

}

?>