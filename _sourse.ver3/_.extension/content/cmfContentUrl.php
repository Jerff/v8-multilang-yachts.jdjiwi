<?php


class cmfContentUrl {

	static public function update() {
        cmfRegister::getSql()->truncate(db_content_url);
//		cmfControllerArenda::updateUri();
		cmfControllerInfo::updateUri();
		cmfControllerContent::updateUri();
	}

 	public static function isUrlExists($page, $id, $uri) {
        $url = '/'. $uri . '/';
//        $res = cmfRegister::getSql()->placeholder("SELECT * FROM ?t WHERE `url`=? AND `url` NOT IN(SELECT `url` FROM ?t WHERE `id`=? AND `page`=?)", db_content_url, $uri, db_content_url, $id, $page);
//        pre($res->numRows(), $res->fetchAssoc());
//        exit;
        return cmfRegister::getSql()->placeholder("SELECT 1 FROM ?t WHERE `part`='main' AND (/*`small_name`=? OR */ `url`=?) AND visible='yes' AND isVisible='yes'", db_pages_main, $url, $url)
									->numRows()
        		or cmfRegister::getSql()->placeholder("SELECT 1 FROM ?t WHERE `url`=? AND `url` NOT IN(SELECT `url` FROM ?t WHERE `id`=? AND `page`=?)", db_content_url, $uri, db_content_url, $id, $page)
									->numRows();
	}

 	public static function delete($page, $list) {
        $where = array('id'=>(array)$list);
        $where[] = 'AND';
        $where['page'] = $page;
        cmfRegister::getSql()->del(db_content_url, $where);
	}

}

?>