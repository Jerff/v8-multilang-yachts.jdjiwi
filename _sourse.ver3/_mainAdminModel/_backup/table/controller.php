<?php


class _backup_table_controller extends driver_controller_edit {

	protected function init() {
		parent::init();

		// url
		$this->setSubmitUrl('/admin/backup/table/');

		$this->callFuncWriteAdd('optimize|exportDumpPages|importDumpPages|exportDump|importDump');
	}

	protected function optimize() {
		cmfBackupTable::optimize();
	}

	public function exportDumpPages() {
        cmfBackupTable::exportDumpPages();
 	}

	protected function importDumpPages() {
		cmfBackupTable::importDumpPages();
		$this->getResponse()->addAlert('Восстановление завершено');
	}

	protected function exportDump() {
		cmfBackupTable::exportDump();
	}

	protected function importDump() {
		cmfBackupTable::importDump();
		$this->getResponse()->addAlert('Восстановление завершено');
	}

}

?>