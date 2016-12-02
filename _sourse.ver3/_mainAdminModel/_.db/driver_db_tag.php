<?php


abstract class driver_db_tag extends driver_db_edit {

	public function attach() {
        return 'product';
	}

	public function runData() {
		$product = $this->attach();
		$res = $this->getSql()->placeholder("SELECT id, name FROM ?t WHERE id IN(SELECT tag FROM ?t WHERE $product=?) ORDER BY name", db_tag, $this->getTable(), $this->getId())
								->fetchAssocAll();
		$send['tag'] = $sep = '';
		foreach($res as $row) {
			$send['tag'] .= $sep . $row['name'];
			$sep = ', ';
		}
		return $send;
	}

	public function saveId($id, $send) {
		$product = $this->attach();
		$sql = $this->getSql();
		$_tagId = array();
		foreach(explode(',', $send['tag']) as $tag) {
			$name = trim($tag);
			$name2 = cmfString::translate($name);
			$id = $sql->placeholder("SELECT id FROM ?t WHERE name=? OR name2=?", db_tag, $name, $name2)->fetchRow(0);
			if(!$id) {
                $id = $sql->add(db_tag, array('name'=>$name, 'name2'=>$name2));
			}
			$_tagId[] = $id;
			$sql->replace($this->getTable(), array('tag'=>$id, $product=>$this->getId()));
		}
		$res = $sql->placeholder("SELECT tag FROM ?t WHERE $product", $this->getTable(), $this->getId())
					->fetchRowAll(0, 0);
		$sql->placeholder("DELETE FROM ?t WHERE $product=? AND tag NOT ?@", $this->getTable(), $this->getId(), $_tagId);
		foreach($res as $id) {
             $sql->placeholder("UPDATE ?t SET $product=(SELECT count(*) FROM ?t WHERE tag=?) WHERE id=?", db_tag, $this->getTable(), $id, $id);
		}
		return $id;
	}

	public function delete(&$form, $list_id) {
		$this->getSql()->del($this->getTable(), array($this->attach()=>$list_id));
		$this->getSql()->optimize($this->getTable());
	}

}

?>