<?php

class cmfControllerMainInfo extends cmfControllerDriver {

    const name = '/mainInfo/';

    static public function update($id = null) {
        self::updateSearch();
    }

    static public function delete($id) {
        cmfSearchData::delete(self::name, $id);
    }

    static public function updateSearch() {
        $sql = cmfRegister::getSql();
        $list = array('main'/* , 'sale/new', 'shipyards', 'brokerage', 'arenda/info', 'charter', 'article', 'news', 'foto', 'wallpapers' */);
//        $fields = array('id', 'header', 'content');
//        $res = $sql->placeholder("SELECT ?fields FROM ?t WHERE id ?@", $fields, db_main, $list)
//                ->fetchAssocAll();
        $fields1 = array('id');
        $fields2 = array('lang', 'header', 'content');
        $res = $sql->placeholder("SELECT ?fields:a, ?fields:b FROM ?t a LEFT JOIN ?t b ON(b.id=a.id) WHERE a.id ?@", $fields1, $fields2, db_main, db_main_lang, $list)
                ->fetchAssocAll();
        foreach ($res as $row) {
            $send = array();
            $send['visible'] = 'yes';
            $send['id'] = $row['id'];
            $send['lang'] = $row['lang'];
            $send['page'] = self::name;
            $send['url'] = cmfGetUrl('/' . $row['id'] . '/');
            $send['content'] = cmfSearchData::reformStr($row['header'] . ' ' . $row['content']);
            $send['notice'] = strip_tags($row['content']);
            $send['name'] = $row['header'];

            cmfSearchData::save($send);
        }
    }

}

?>