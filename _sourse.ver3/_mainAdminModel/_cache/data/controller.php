<?php


class _cache_data_controller extends driver_controller_edit {

	protected function init() {
		parent::init();

		// url
		$this->setSubmitUrl('/admin/cache/data/');

		$this->callFuncWriteAdd('clearCache');
	}


	public function runCommand($command=null) {
		set_time_limit(0);
		if(cmfAjax::isCommand('updateCache') or $command==='updateCache') {
			cmfCronCacheUpdate::start();
		}
		if(cmfAjax::isCommand('updateSearch') or $command==='updateSearch') {
			cmfCronUpdateSearch::init();
		}
	}


	public function updateCache() {
        $updateUrl = cmfToJsString(cmfGetAdminCommand('/admin/cache/data/') ."&command=updateCache");
		cmfAjax::get()->addScript("cmf.ajax.command('{$updateUrl}', 'Обновлние кеша');");
	}

	public function updateSearch() {
        $updateUrl = cmfToJsString(cmfGetAdminCommand('/admin/cache/data/') ."&command=updateSearch");
		cmfAjax::get()->addScript("cmf.ajax.command('{$updateUrl}', 'Обновление поискового индекса');");
	}

}

?>