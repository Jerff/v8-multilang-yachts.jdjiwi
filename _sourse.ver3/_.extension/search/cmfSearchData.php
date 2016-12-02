<?php



class cmfSearchData {
    static public function reformStr($str) {
        $str = strip_tags($str);
        $str = preg_replace('~([^a-zа-я0-9])~iu', ' ', $str);
		$str = preg_replace('~\s{2,}~', ' ', $str);
        $str = trim($str);
		$str = substr($str, 0, 50000);
		return $str;
	}

    static public function save($send) {
        foreach($send as $k=>$v) {
            if(is_array($v)) {
                foreach($v as $k2=>$v2) {
                    $send[$k] = $v2;
                    self::save($send);
                }
                return;
            }
        }
        $send['created'] = time();
        cmfRegister::getSql()->replace(db_search, $send);
	}

    static public function update() {
        cmfControllerContent::updateSearch();
        cmfControllerInfo::updateSearch();
        cmfControllerMainInfo::updateSearch();

        cmfControllerArticle::updateSearch();
        cmfControllerNews::updateSearch();
        cmfControllerBlog::updateSearch();

        cmfControllerArenda::updateSearch();
        cmfControllerArendaYachts::updateSearch();

        cmfConfigBrokerageYachts::updateSearch();

        cmfControllerShipyards::updateSearch();
        cmfControllerShipyardsYachts::updateSearch();
	}

    public static function delete($page, $list) {
        $where = array('id'=>(array)$list);
        $where[] = 'AND';
        $where['page'] = $page;
        cmfRegister::getSql()->del(db_search, $where);
	}

}

?>