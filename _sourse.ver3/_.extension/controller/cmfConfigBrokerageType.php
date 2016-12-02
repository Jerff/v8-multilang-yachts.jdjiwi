<?php

class cmfConfigBrokerageType extends cmfControllerDriver {

    const name = '/brokerage/type/';

    static public function update($id = null) {
        self::updateSearch($id);
        self::updateUri($id);
    }

    static public function delete($id) {
        cmfSearchData::delete(self::name, $id);
    }

    static public function updateSearch($id = null) {

    }

    static public function updateUri($where1 = null) {
        self::updateWhere($where1);
        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'main', i.id, t.id, 0, CONCAT(i.isUri, '/', t.uri) FROM ?t t LEFT JOIN ?t i ON(i.pages='brokerage') WHERE ?w:t", db_content_url, self::name, db_brokerage_type, db_menu_info, $where1);

        cmfConfigBrokerageYachts::updateUri($where1);
    }

}

?>