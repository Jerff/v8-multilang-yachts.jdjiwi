<?php

cmfLoad('Mobile-Detect/Mobile_Detect');

class cMobileDetect {

    private static function get() {
        static $driver = null;
        if(empty($driver)) {
            $driver = new Mobile_Detect();
        }
        return $driver;
    }
    public static function isMobile() {
        return true;
        return self::get()->isMobile();
    }

}

?>