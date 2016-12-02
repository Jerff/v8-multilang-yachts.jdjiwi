<?php

class table_data_controller extends driver_controller_list_all_lang {

    protected function init() {
        parent::init();
        $this->addModul('main', 'table_data_modul');
//        $this->addModul('lang', 'table_data_lang_modul');

        // url
        $this->setSubmitUrl('/admin/table/data/');

        $this->callFuncWriteAdd('newLine');
    }

    public function filterTable() {
        $filter = cmfModulLoad('table_list_db')->getNameList();
        return parent::abstractFilterPart($filter, 'table', 'reset');
    }

    public function initTable() {
        $data = cmfModulLoad('table_list_db')->getDataId($this->getFilter('table'));
        cmfGlobal::set('fieldColum', (int)get($data, 'colum'));
        cmfGlobal::set('filedList', cmfString::unserialize(get($data, 'field')));
    }

	public function deleteTable($id) {
		$this->delete($this->getListId(array('table'=>$id)));
	}

    public function delete($id) {
        parent::deleteList($id);
        return parent::delete($id);
    }

}

?>