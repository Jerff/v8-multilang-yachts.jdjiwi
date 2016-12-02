<?php


abstract class driver_db_lang_edit extends driver_db_edit {


    protected function getWhereId($id) {
        return array('id'=>$id, 'AND', 'lang'=>cmfLang::getId());
	}

	public function setNotFount() {
	}

	public function langLoadData() {
	    return $this->getSql()->placeholder("SELECT * FROM ?t WHERE id=?", $this->getTable(), $this->getId())
									->fetchAssocAll('lang');
	}

}

?>