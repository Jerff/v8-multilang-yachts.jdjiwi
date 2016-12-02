<?php


class cmfControllerShipyardsType extends cmfControllerDriver {
    const name = '/shipyards/type/';
    const sale = '/sale/new/type/';
    static public function update($id) {
        self::updateSearch($id);
        cmfControllerShipyards::updateCountYachts();
        self::updateUri($id);
	}


    static public function delete($id) {
        cmfSearchData::delete(self::name, $id);
        cmfControllerShipyards::updateCountYachts();
	}


    static public function updateSearch($id) {
        cmfControllerShipyardsYachts::updateSearch(array('type'=>$id));
	}

    static public function updateUri($where1 = null) {
        self::updateWhere($where1);
        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'main', i.id, s.id, 0, CONCAT(i.isUri, '/', s.uri) FROM ?t s LEFT JOIN ?t i ON(i.pages='shipyards') WHERE ?w:s", db_content_url, self::name, db_shipyards_type, db_menu_info, $where1);
        cmfRegister::getSql()->placeholder("
                REPLACE ?t SELECT ?, 'main', i.id, s.id, 0, CONCAT(i.isUri, '/', s.uri) FROM ?t s LEFT JOIN ?t i ON(i.pages='new_sale') WHERE ?w:s", db_content_url, self::sale, db_shipyards_type, db_menu_info, $where1);

//        cmfControllerShipyardsYachts::updateUri($where1);
    }


}

?>