<?php


class cmfAdminTemplate {


	private $teplates = null;
	private $values = array();


	public function __construct(){
		$this->setTeplates('admin.index.php');
	}


	public function setTeplates($t) {
		$this->teplates = $t;
	}
	public function getTeplates() {
		if(cmfAjax::isCommand('start')) {
			return str_replace('.php', '.start.php', $this->teplates);
		} else {
			return $this->teplates;
		}
	}


	public function main() {
		$page = cmfPages::getMain();
		$_page = cmfPages::getPage($page);
		$this->setTeplates(cmfPages::getTemplates($_page['t']));

		$content = $this->run($page);
        include cmfAdminTeplates . $this->getTeplates();
	}

	public function run($page) {
		$_page = cmfPages::getPage($page);
		if(is_null($_page)) return false;

/*		if(isset($_page['read'])) {
			$access = false;
			foreach((array)cmfRegister::getAdmin()->getGroup() as $group) {
				if(in_array($group, $_page['read'])) {
					$access = true;
					break;
				}
			}
			if(!$access) cmfAccess::noAccess();
		} else {
			if(!isset($_page['access'])) cmfAccess::noAccess();
		}*/


		ob_start();
		cmfPages::setItem($page);
		if($this->processingPhp($_page['path'])===cmfAdminNotRecord) {
			$this->processingPhtml('_404');
		} else {
			$this->processingPhtml($_page['path']);
		}
		return ob_get_clean();
	}


	private function assing($name, $value) {
        $this->values[$name] = $value;
	}

	private function processingPhp($p58) {
		return require(cmfAdminModel . $p58 .'.php');
	}

	private function processingPhtml($p58) {
		extract($this->values);
		$this->values = array();
		if(is_file(cmfAdminView . $p58 . '.phtml')) {
			require(cmfAdminView . $p58 . '.phtml');
		} else {
			require(cmfAdminModel . $p58 . '.phtml');
		}

	}

}

?>