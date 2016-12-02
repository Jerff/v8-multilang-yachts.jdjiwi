<?php

cmfLoad('pages/cmfPagesDriver');
cmfLoad('pages/admin/function');
cmfLoad('pages/main/function');
class cmfPages extends cmfPagesDriver {

	public static function select(&$_p, &$t) {
		self::setPage($_p);
		self::setTemplates($t);

		if(!cmfRegister::getAdmin()->is()) {
			self::setMain('/admin/enter/');
			return;
		}

		if(cmfRegister::getRequest()->isGet('url')) {
			$url = cmfRegister::getRequest()->getGet('url');

		} else
		if(!cmfAjax::isAjax()) {
			if(cmfRegister::getAdmin()->is()) {
				self::setMain('/admin/index/');
			} else {
				self::setMain('/admin/enter/');
			}
			return;

		} else {
			//cmfDebug::setError();
            //cmfDebug::setSql();

			$url = cmfAjax::getUrl();
			preg_match('~'. preg_quote(cmfAdminUrl) . cmfLang::getPregUrl() .'(.*)\#(\&?.*)~', $url, $tmp);
			if(!empty($tmp[2])) {
                cmfLang::select($tmp[2]);
			}
			$url = empty($tmp[3]) ? '/' : $tmp[3];
		}

		if(!empty($_SERVER['HTTP_REFERER'])) {
			if(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)!==cmfDomen) {
				self::setMain('/admin/index/');
				return;
			}
		}

		if(!empty($tmp[4])) {
			$r = cmfRegister::getRequest();
			foreach(explode('&', $tmp[4]) as $v) if($v) {
				$v = explode('=', $v);
				if(isset($v[1])) {
                    $r->setGet($v[0], $v[1]);
				} else {
					$r->setGet($v[0], 1);
				}
			}
		}

		$page = false;
		$_param = null;
		while(list($k, $v) = each($_p)) {
			if(isset($v['part'])) continue;
			if(isset($v['preg'])) {
				foreach($v['preg'] as $p) {
					if(preg_match($p, $url, $pr)) {
						$page = $k;
						$_param = $pr;
						break;
					}
				}
				if(!is_null($_param)) break;
			}
			if(isset($v['url']) and $v['url']===$url) {
				$page = $k;
				break;
			}
		}
		if(!$page) {
			cmfAjax::get()->addAlert('Ничего не найдено!');
			exit;
		}

		if($_param) array_shift($_param);
		self::setMain($page);
		self::setParam($_param);
	}

}

?>
