<?php

class cmfControllerInfo extends cmfControllerDriver {

    const name = '/info/';

    static public function update($id = null) {
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
//        $fields = array('id', 'visible', 'name', 'content', 'isUri');
//        $res = $sql->placeholder("SELECT ?fields FROM ?t WHERE ?w", $fields, db_menu_info, $where)
//                ->fetchAssocAll();
        $fields1 = array('id', 'visible', 'isUri');
        $fields2 = array('lang', 'name', 'content');
        $res = $sql->placeholder("SELECT ?fields:a, ?fields:b FROM ?t a LEFT JOIN ?t b ON(b.id=a.id) WHERE ?w:a", $fields1, $fields2, db_menu_info, db_menu_info_lang, $where)
                ->fetchAssocAll();
        foreach ($res as $row) {
            $send = array();
            $send['visible'] = $row['visible'];
            $send['id'] = $row['id'];
            $send['lang'] = $row['lang'];
            $send['page'] = self::name;
            $send['url'] = cmfGetUrl('/info/', array($row['isUri']));
            $send['content'] = cmfSearchData::reformStr($row['name'] . ' ' . $row['content']);
            $send['notice'] = strip_tags($row['content']);
            $send['name'] = $row['name'];
            cmfSearchData::save($send);
        }
    }

    static public function updateUri($where = null) {
        self::updateWhere($where);
        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'main', id, 0, 0, isUri FROM ?t WHERE ?w", db_content_url, self::name, db_menu_info, $where);
        cmfControllerNews::updateUri();
        cmfControllerArticle::updateUri();
        cmfControllerBlog::updateUri();
        cmfControllerBoard::updateUri();
        cmfControllerArenda::updateUri();
        cmfControllerArendaType::updateUri();
        cmfConfigBrokerageType::updateUri();
        cmfControllerShipyards::updateUri();
        cmfControllerShipyardsType::updateUri();
    }

}

?>