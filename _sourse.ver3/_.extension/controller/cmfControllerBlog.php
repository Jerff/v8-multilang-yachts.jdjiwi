<?php

class cmfControllerBlog extends cmfControllerDriver {

    const name = '/blog/item/';

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
        $fields2 = array('lang', 'name', 'notice', 'content');
        $res = $sql->placeholder("SELECT ?fields:a, ?fields:b FROM ?t a LEFT JOIN ?t b ON(b.id=a.id) WHERE ?w:a", $fields1, $fields2, db_blog, db_blog_lang, $where)
                ->fetchAssocAll();
        foreach ($res as $row) {
            $send = array();
            $send['visible'] = $row['visible'];
            $send['id'] = $row['id'];
            $send['page'] = self::name;
            $send['url'] = cmfGetUrl('/blog/item/', array($row['uri']));
            $send['content'] = cmfSearchData::reformStr($row['name'] . ' ' . $row['notice'] . ' ' . $row['content']);
            $send['notice'] = strip_tags($row['notice']);
            $send['name'] = $row['name'];;
            cmfSearchData::save($send);
        }
    }

    static public function updateUri($where = null) {
        self::updateWhere($where);
        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'blog', i.id, n.id, 0, CONCAT(n.uri) FROM ?t n LEFT JOIN ?t i ON(i.pages='blog') WHERE ?w:n", db_content_url, self::name, db_blog, db_menu_info, $where);
    }

}

?>