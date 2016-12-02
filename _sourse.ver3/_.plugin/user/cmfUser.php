<?php

cmfLoad('user/model/cmfDriverUser');
cmfLoad('user/authorization/cmfAuth');
class cmfUser extends cmfAuth {

	protected function getName() {
	    return 'sessionUser';
	}

    // ������ ������ ������
    protected function getFields() {
		return array('id', 'login', 'email');
	}
    // ������ �������������� ������
	public function getFieldsParam() {
		return array('id');
	}

	// ������
	public function filterIsUser(){
		if($this->is()) return;
		cmfRedirect(cmfGetUrl('/user/enter/'));
	}
	public function filterNoUser(){
		if(!$this->is()) return;
		if(isset($_GET['fancybox'])) {
		    ?>
            <script type="text/javascript">
            cmf.redirect('<?=cmfGetUrl('/user/') ?>');
            </script>
		    <?
            exit;
		} else {
		    cmfRedirect(cmfGetUrl('/user/'));
		}
	}


	protected function sessionUpdate() {
        $data = $this->getData();
		cmfCookie::set($this->getName() .'Email', $data['email']);
	}


	protected function sessionRemove() {
		parent::sessionRemove();
		cmfCookie::del($this->getName() .'Name');
		cmfCacheUser::setDiscount(0);
	}

    // ������������
//	public function logOut(){
//		parent::logOut();
//	}

	public static function generateName($send){
		$name = array();
        if(isset($send['login'])) {
            $name[] = $send['login'];
        }
		return implode(' ', $name);
	}

	public static function getUserId() {
		return cmfGlobal::get('$userId');
	}

}

?>