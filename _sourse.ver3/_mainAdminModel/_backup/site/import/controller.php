<?php


class _backup_site_import_controller extends driver_controller_edit {

	protected function init() {
		parent::init();

		// url
		$this->setSubmitUrl('/admin/backup/site/');
		$this->callFuncWriteAdd('importBackup');
		$this->callFuncReadAdd('selectBackup');
	}

	public function getFileList() {
	    return cmfBackupSite::getFileList();
	}

	protected function selectBackup() {
	    $res = cmfBackupSite::getFileList();
	    $file = $this->getRequest()->getPost('backup');
	    if(!isset($res[$file])) {
	        return;
	    }
	    $res = $res[$file];

	    $content = $res['name'] .'<div class="empty"></div>';
	    foreach($res['modul'] as $k=>$v) {
	        $content .= '<label><input name="select['. $k .']" type="checkbox">&nbsp;'. $v .'</label>';
	    }
	    $content .= '<div class="empty"></div>'.
	                cmfAdminView::onclickType1("if(confirm('Загрузить дамп?')) edit.postAjax('importBackup');", 'Загрузить дамп');
	    $this->getResponse()->loadHTML('selectDump', $content);
	}

	protected function importBackup() {
	    $res = cmfBackupSite::getFileList();
	    $file = $this->getRequest()->getPost('backup');
	    if(!isset($res[$file])) {
	        return;
	    }
	    $res = $res[$file]['modul'];
	    $select = $this->getRequest()->getPost('select');
	    foreach($res as $k=>$v) {
	        if(!isset($select[$k])) {
	            unset($res[$k]);
	        }
	    }
	    if(!$res) {
	        return;
	    }
	    cmfBackupSite::import($file, $res);
	    $this->getResponse()->addAlert('Восстановление завершено');
	}

}

?>