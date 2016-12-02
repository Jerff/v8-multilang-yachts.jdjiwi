<?php

class cmfControllerNews extends cmfControllerDriver {

    const name = '/news/item/';

    static public function update($id) {
        self::updateUri($id);
        self::updateSearch($id);
    }

    static public function delete($id) {
        cmfContentUrl::delete(self::name, $id);
        cmfSearchData::delete(self::name, $id);
    }

    static public function updateSearch($where = null) {
        self::updateWhere($where);
        $sql = cmfRegister::getSql();
        $fields1 = array('id', 'visible', 'uri');
        $fields2 = array('lang', 'header', 'notice', 'content');
        $res = $sql->placeholder("SELECT ?fields:a, ?fields:b FROM ?t a LEFT JOIN ?t b ON(b.id=a.id) WHERE ?w:a", $fields1, $fields2, db_news, db_news_lang, $where)
                ->fetchAssocAll();
        foreach ($res as $row) {
            $send = array();
            $send['visible'] = $row['visible'];
            $send['id'] = $row['id'];
            $send['lang'] = $row['lang'];
            $send['page'] = self::name;
            $send['url'] = cmfGetUrl('/news/item/', array($row['uri']));
            $send['content'] = cmfSearchData::reformStr($row['header'] . ' ' . $row['notice'] . ' ' . $row['content']);
            $send['notice'] = strip_tags($row['notice']);
            $send['name'] = $row['header'];
            cmfSearchData::save($send);
        }
    }

    static public function updateUri($where = null) {
        self::updateWhere($where);
        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'main', i.id, n.id, 0, CONCAT(i.isUri, '/', n.uri) FROM ?t n LEFT JOIN ?t i ON(i.pages='news') WHERE ?w:n", db_content_url, self::name, db_news, db_menu_info, $where);
    }

}

?>