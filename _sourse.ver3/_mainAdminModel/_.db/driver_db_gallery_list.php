<?php


abstract class driver_db_gallery_list extends driver_db_list {


	public function loadData(&$row) {
		if(cmfStrLen($row['name'])>12) {
		    $row['header'] = cmfSubStr($row['name'], 0, 12) .'...';
		} elseif(empty($row['name'])) {
		    $row['header'] = 'редактировать';
		} else {
		    $row['header'] = $row['name'];
		}
		parent::loadData($row);
	}

}

?>