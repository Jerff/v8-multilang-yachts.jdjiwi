<?php


class cmfControllerArendaType extends cmfControllerDriver {
    const name = '/arenda/type/';
    static public function update($id=null) {
        self::updateUri($id);
        self::updateSearch($id);
        cmfControllerArenda::updateCountYachts();
	}
    static public function delete($id) {
        cmfContentUrl::delete(self::name, array('param1'=>$id));
        cmfSearchData::delete(self::name, $id);
        cmfConfigArenda::updateCountYachts();
	}

    static public function updateSearch($id=null) {
	}

    static public function updateUri($where1=null) {
        self::updateWhere($where1);
        cmfControllerArendaYachts::updateUri($where1);
	}

}

?>