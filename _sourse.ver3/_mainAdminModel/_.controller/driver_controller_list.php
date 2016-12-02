<?php


abstract class driver_controller_list extends driver_controller_edit implements Iterator {

	// список id текущих выбранных записей
	private $dataId = null;

	// нужно ли сколько всего записей или нет
	private $isTotal = true;

	// количество всего записей подходящих под условие
	private $total = null;


	function __construct() {
		parent::__construct();

		if($m=$this->getModul()) {
			if($m->getNewPos()) {
				$this->callFuncWriteAdd('moveMoveAjax|move1|move2');
			}
		}
		$this->callFuncDeleteAdd('deleteRecord');
		//$this->callFuncWriteAdd('delete|newLine');
	}


	// работа с урлами ---------------
	public function initRequest() {
		// установка правильного лемита
		$limit = $this->getRequest()->getGet('limit', 20);
		$limitAll = $this->getLimitAll();
		if(!in_array($limit, $limitAll)) {
			$limit = current($limitAll);
		}
		$this->getRequest()->setGet('limit', $limit);
		parent::initRequest();
	}

	public function setEditUrl($url) {
		$this->setUrl('edit', $url);
	}
	public function getEditUrl($opt=null) {
		$opt['parentId'] = null;
		return $this->getUrl('edit', $opt);
	}

	public function setNewUrl($url) {
		$this->setUrl('edit', $url);
	}
	public function getNewUrl($opt=null) {
		$opt['id'] = $opt['parentId'] = null;
		return $this->getUrl('edit', $opt);
	}



	// текущия страница
	protected function getPage() {
		$pg = (int)$this->getFilter('page');
		return $pg >1 ? $pg: 1;
	}

	// установить & вернуть количество показываемых записей на страницу
	protected function setLimit($limit) {
		$this->setFilter('limit', $limit);
	}
	protected function getLimit() {
		$limit = $this->getFilter('limit');
		if($limit==='all') return 100;
		else return (int)$limit;
	}


	// установка & возврат лимита ссылок страниц на страницу
	protected function getLimitPage() {
		return 20;
	}
	// установка & возврат всех возможных лимитов записей
	protected function getLimitAll() {
		return array(20, 30, 'all');
	}



	// общее количество записей
	// устанвливает & возвращает общее количество записей
	protected function setTotal($total) {
		$this->total = $total;
	}
	public function getTotal() {
		return $this->total;
	}

	// устанвливает & возвращает нужно ли узнавать общее количество записей
	protected function setIsTotal($is=false) {
		$this->isTotal = $is;
	}
	public function getIsTotal() {
		return $this->isTotal;
	}



	// возвращает ссылки на все страницы
	public function &getLinkPage() {
		$total= $this->getTotal();
		if(!$total) {
			$page_link = array();
			return $page_link;
		}

		$page = $this->getPage();
		$page_link = cmfAdminPagination($page, $total, $this->getLimit(), $this->getLimitPage());
		cmfAdminView::pagination($this->getSubmitUrl(), $page, $page_link);
		return $page_link;
	}

	// возвращает возможные limit ссылки
	public function &getLimitUrl() {
		$url = $this->getSubmitUrl(array('page'=>1));
		$limit_url = array();

		foreach($this->getLimitAll() as $value) {
			$limit_url[$value] = array(	'name'=>($value==='all') ? 'Все' : $value,
										'url'=>$url ."&limit={$value}" );
		}

		$limit_url[$this->getFilter('limit')]['sel'] = 1;
		return $limit_url;
	}






	// устанавливает список id текущих выбранных записей
	protected function setDataId($id) {
		$this->dataId = $id;
	}

	// возвращает список id текущих выбранных записей
	public function getDataId() {
		return $this->dataId;
	}

	// заполнение форм данными из базы
	public function run($id=null) {
		if(is_null($id)) {
			$page = $this->getPage();
			$limit = $this->getLimit();
			$offset = ($page-1) * $limit;

			if(!$modul = $this->getModul()) return;
			$id = $modul->runList($id, $offset, $limit);
			if($this->getIsTotal()) {
				$this->setTotal($modul->getTotal());
			} else {
				$this->setTotal(count($id));
			}
			unset($modul);
		} else  {

			$this->getModul()->runList($id);
			if($this->getIsTotal()) {
				$this->setTotal(count($id));
			}
		}
		$this->setDataId($id);

		$modulAll = $this->getModulAll();
		while(list($name, $modul) = each($modulAll))
			if($name!=='main') $modul->runList($id);
	}



	// удаление записей
	public function deleteRecord($id) {
		$id = $this->delete($id);
		$this->deleteView($id);
	}
	public function delete($id) {
		return $id;
	}
	protected function deleteList($id) {
		return parent::delete($id);
	}
	protected function deleteView($dataId) {
		$js = '';
		$modul = $this->getJsModulName();
		foreach((array)$dataId as $id) {
			$js .= "{$modul}.deleteLine('". $this->getHtmlIdDel($id) ."');";
		}
		$response = $this->getResponse();
		if($response) $response->addScript($js);
	}
	// --------------- /заполнение формами данных БД ---------------








	protected function copyInit() {
		$this->setNewView();
	}



	// обновление форм
	protected function update() {
		$this->setUpdate();

		$list = array();
		$new = array();
		foreach((array)$this->getRequest()->getPost($this->getName()) as $key=>$id) {
			if($key==='{0}') continue;

			if($id) {
    			$list[$key] = self::filterId($id);
			} else {
				$new[$key] = self::filterId($id);
			}
		}

		if(!count($list) and !count($new)) return;

		$isError = false;
		$modulAll = $this->getModulAll();
		while(list(, $modul) = each($modulAll)) {
			if($list) {
				$modul->updateIsErrorList($list, $isError);
			}
			if($new) {
				$modul->updateIsErrorList($new, $isError);
			}
			if($isError) break;
		}
		$js = '';
		reset($modulAll);
		while(list($name, $modul) = each($modulAll)) {
			if($isError) {
				if($list) $js .= $modul->updateErrorList($list);
			} else {
				if($list) $modul->updateListOk($list);
			}
		}
		if($isError) {
            if($new) $js .= $modul->updateErrorList($new);
        } else {
            if($new) $this->newLineOk($new);
        }

		if($this->isReload()) {
            $this->getResponse()->addRedirect($this->getSubmitUrl());
		}

		if($this->isNewView()) {
			return;
		}

		if(!$isError) {
			reset($modulAll);
			while(list($name, $modul) = each($modulAll)) {
				$js .= $modul->getJsSetDataList($name, $this, $list);
			}
		}

		$response = $this->getResponse();
		$response->addScript($js);
	}

	// удаление файла
	protected function delFile($name, $element, $id=null) {
		if($id) $this->setId($id);
		parent::delFile($name, $element);
		die();
	}


	// перемещение взад вперед строк, изменение позиции
	public function getPostMove($data) {
        return (view_list::posMove($this->getModulName(), $this->getName(), $this->getKey(), $data->pos));
	}

	protected function moveMoveAjax($id, $value) {
        $this->setNewView();
        $this->setId($id);
        $this->getModul()->save(array('pos'=>(int)$value));
	}

	protected function move1($id, $type=null) {
		if($id2=$this->getModul()->getDb()->move1($id)) {
			list($id2, $pos1, $pos2) = $id2;
			$name = $this->getName();
			$this->getResponse()->addScript("
			$('#{$name}posView{$id}').html('{$pos2}');
			$('#{$name}posView{$id2}').html('{$pos1}');");

			$id2 = $this->getHtmlIdDel($id2);
			$modul = $this->getJsModulName();
			$id = $this->getHtmlIdDel($id);
			if(!$type) $this->getResponse()->addScript("{$modul}.move1('{$id}', '{$id2}');");
			else $this->getResponse()->addScript("{$modul}.move2('{$id}', '{$id2}');");
			return true;
		}
		return false;
	}

	protected function move2($id, $type=null) {
		if($id2=$this->getModul()->getDb()->move2($id)) {
			list($id2, $pos1, $pos2) = $id2;
			$name = $this->getName();
			$this->getResponse()->addScript("
			$('#{$name}posView{$id}').html('{$pos2}');
			$('#{$name}posView{$id2}').html('{$pos1}');");

			$id2 = $this->getHtmlIdDel($id2);
			$modul = $this->getJsModulName();
			$id = $this->getHtmlIdDel($id);
			if(!$type) $this->getResponse()->addScript("{$modul}.move2('{$id}', '{$id2}');");
			else $this->getResponse()->addScript("{$modul}.move1('{$id}', '{$id2}');");
			return true;
		}
		return false;
	}




	public function abstractFilterPart($filter, $key, $sort='reset') {
		$filterId = urldecode($this->getFilter($key));
		if(!isset($filter[$filterId])) {
			if($sort==='reset') {
                reset($filter);
				$filterId = key($filter);
			} elseif($sort==='end') {
				end($filter);
				$filterId = key($filter);
			} else {
				if(isset($filter[$sort])) {
                    $filterId = $sort;
				} else {
					$filterId = key($filter);
				}
			}
		}

		$this->setFilter($key, urlencode($filterId));
		if(isset($filter[$filterId])) $filter[$filterId]['sel'] = true;

		$url = $this->getSubmitUrl(array($key=>null, 'page'=>1));
		foreach($filter as $id=>&$value)
			$value['url'] = $url .'&'. urlencode($key) .'='. urlencode($id);

		return $filter;
	}





	// --------------- вывод html ---------------
	// html - список показанных на странице id
	public function getHtmlListId() {
		return view_list::hideInput($this->getName() ."[". $this->getIndex() ."]", $this->getKey());
	}

	// html - id строки успользумой для скрытия удаленных данных
	public function getHtmlIdDel($id=null) {
		if(is_null($id)) $id = $this->getIndex();
		return $this->getName().'_id_del_'.$id;
	}
	// --------------- вывод html ---------------










	// --------------- интерфейс Iterator к foreach ---------------
	public function rewind() {
		$k = $v = $this->dataId;
		if(!array_search('{0}', $k)) {
    		array_unshift($k, '{0}');
    		array_unshift($v, 0);
            $this->dataId = array_combine($k, $v);
		} else {
		    reset($this->dataId);
		}
	}
	public function &current() {
		$r = new cmfData();
		$id = $this->getKey();
		$index = $this->getIndex();

		$modulAll = $this->getModulAll();
		while(list($n, $m) = each($modulAll)) {
			$r->$n = $m->currentList($index, $id);
		}
		return $r;
	}
	protected function getKey() {
		return current($this->dataId);
	}
	public function getIndex() {
		return key($this->dataId);
	}
	public function key() {
		$this->setId($id = $this->getKey());
		return $id;
	}
	public function next() {
		return next($this->dataId);
	}
	public function valid() {
		return current($this->dataId) !== false;
	}
	// --------------- интерфейс Iterator к foreach ---------------




	/* new line */
	protected function &getNewLine() {
		if($this->returnParent()) {
            return cmfModulLoad($this->returnParent());
		} else {
			return $this;
		}
	}

	protected function newLine() {
		$index = 't'. time();
		$id = 0;
		$modul = $this->getNewLine();
		$modul->setId($id);
		$data = $modul->getNewLineData();
		unset($modul);

		$this->setId($id);
		$result = array();
		$modulAll = $this->getModulAll();
		$js = '';
		while(list($name, $modul) = each($modulAll)) {
			$value = get($data, $name);
			$modul->loadForm();
			$js .= $modul->loadFormNewLine($index);
			$form = $modul->newLine($index, $value);

			$file = $form->getListFile();
			foreach($file as $f) {
				$this->getJsFile($name, $f);
			}

			$result[$name] = $value;
			$js .= $form->jsUpdate();
		}

		$jsModul = $this->getJsModulName();

		$js2 = "{$jsModul}.newLine('{$index}', ". json_encode($result) .");";
		$this->getResponse()->addScript($js2);
		$this->getResponse()->addScript($js);

	}

	protected function newLineOk($new) {
        $controller = $this->getNewLine();
        $modulAll = $this->getModulAll();
        foreach($new as $index=>$id) {
        	reset($modulAll);
        	$formName = array();
        	while(list($name, $modul) = each($modulAll)) {
                $formName[$name] = $modul->getNameID($index);
        	}
            $controller->saveLineOk($formName);
        }
	}

	protected function deleteFile($name, $element, $id) {
        $this->setId($id);
        cmfAccess::isWrite($this);
        parent::deleteFile($name, $element, $id);
	}

}

?>
