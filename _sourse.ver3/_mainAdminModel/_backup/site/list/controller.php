<?php


class _backup_site_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->addModul('main',	'_backup_site_list_modul');

		// url
		$this->setSubmitUrl('/admin/backup/site/');
		$this->callFuncWriteAdd('newLine|resetDump');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

    protected function resetDump($id) {
        $this->setId($id);
        $this->getModul()->getDb()->save(array('status'=>'none'));
        $this->getResponse()->loadHTML('#status'. $id, 'Поставлен на обновление');
    }

}

?>