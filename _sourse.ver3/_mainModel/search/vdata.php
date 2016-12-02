<?php


cmfDebug::destroy();
$offset = (int)cmfPages::getParam(2);
if(!$offset) exit;
if($offset>3000) exit;

$sql = cmfRegister::getSql();
$searchUri = cmfPages::getParam(1);
$sort = $where = array();
$where['visible'] = 'yes';
if($searchUri) {
    $name = urldecode($searchUri);
    $where[] = 'AND';
     if(strlen($name)>4) {
        $isSearh = true;
        $where[] = $sql->getQuery("MATCH (`content`) AGAINST (? IN BOOLEAN MODE)", $name);
        $sort['function'] = $sql->getQuery("MATCH (`content`) AGAINST (?) DESC", $name);
	} else {
		$where[] = $sql->getQuery("`content` LIKE '%?s%'", $name);
		$sort['created'] = 'DESC';
	}
}

$limit = 20;
$search = $sql->placeholder("SELECT url, name, notice FROM ?t WHERE ?w GROUP BY id ORDER BY ?o LIMIT ?i, ?i", db_search, $where, $sort, $offset, $limit)
                                ->fetchAssocAll();
if($search) {
    cmfLoad('search/function');
    cmfSearcView($search, $searchUri);
    $this->assing('search', $search);
}

?>
