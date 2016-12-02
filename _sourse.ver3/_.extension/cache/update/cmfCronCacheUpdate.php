<?php


class cmfCronCacheUpdate {


    static public function start() {
        cmfUpdateCompile::start();
        self::updateData();
        self::updateCache();
    }

	static public function updateData() {
		cmfCronRun::run();
		cmfContentUrl::update();
	}

    static private function updateCache() {
		cmfCronRun::run();
        cmfRegister::getSql()->truncate(db_cache_update);
        cmfCache::clear();
        //cmfDir::clear(cmfCacheSite);
        //cmfDir::clear(cmfCachePage);
    }

}

?>