<?php


class cmfAjaxResponse {

	private $js = null;
	private $loadHTML = null;

	public function __construct() {
		global $_RESULT;
		$_RESULT['js'] = '';
		$_RESULT['loadHTML'] = '';
		$_RESULT['jsAjaxId'] =  cmfRegister::getRequest()->getPost('jsAjaxId');
		$this->js = &$_RESULT['js'];
		$this->loadHTML = &$_RESULT['loadHTML'];
	}


	public function hash($hash) {
		return $this->script("document.location.hash = '{$hash}';");
	}


	public function alert($content) {
		return $this->script('alert("'. cmfToJsString($content) .'");');
	}
	public function addAlert($content) {
		return $this->alert($content);
	}


	public function script($js) {
		$this->js .= "\n". $js;
		return $this;
	}
	public function addScript($js) {
		return $this->script($js);
	}


	public function html($id, $content) {
		if(!preg_match('~([^a-z0-9_])~is', $id)) {
       		$id = '#'. $id;
		}
		$this->loadHTML[] = array('id'=>$id, 'content'=>$content);
		return $this;
	}
	public function loadHTML($id, $content) {
		return $this->html('#'. $id, $content);
	}


	public function assign() {
		if(func_num_args()<3) return;

		$d = func_get_args();
		$k = cmfToJsString(array_shift($d));
		$v = cmfToJsString(array_pop($d));
		$js = "cmf.getId('$k')";

		reset($d);
		while(list(, $v2) = each($d)) {
			$js .= '.' . $v2;
		}
		$js .= " = '$v'";

		$this->script($js);
	}

	public function redirect($u) {
		$this->script("cmf.redirect('$u');");
		exit;
	}
	public function addRedirect($js) {
		$this->redirect($js);
	}

	public function reload() {
		$this->script("cmf.reload();");
		exit;
	}
	public function addReload() {
		$this->reload($js);
	}

}

?>