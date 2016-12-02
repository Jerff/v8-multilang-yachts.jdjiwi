<?php

cmfLoad('sql/cmfMySql');
cmfLoad('user/cmfAdmin');
cmfLoad('user/cmfUser');

class cmfRegister {

    static private $config = array();

    static public function &get($class) {
        if (!isset(self::$config[$class])) {
            self::$config[$class] = new $class();
        }
        return self::$config[$class];
    }

    static private function free($class) {
        self::$config[$class]->free();
        unset(self::$config[$class]);
    }

    // получить экземпляр cmfRequest
    public static function getRequest() {
        return self::get('cmfRequest');
    }

    // получить экземпляр cmfPDO
    public static function getSql() {
        return self::get('cmfMySql');
    }

    // получить экземпляр Пользователя
    public static function getUser() {
        return self::get('cmfUser');
    }

    // получить экземпляр Админа
    private static $admin = null;

    public static function &getAdmin() {
        return self::get('cmfAdmin');
    }

    public static function getAdminId() {
        return self::getAdmin()->getId();
    }

}

?>