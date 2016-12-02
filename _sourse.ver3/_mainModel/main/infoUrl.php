<?php



$infoUri = cmfPages::getParam(1);
if(!$infoUri) return 404;
$row = cmfRegister::getSql()->placeholder("SELECT id, param1, param2, page FROM ?t WHERE url=?", db_content_url, $infoUri)
							->fetchRow();
if(!$row) {
    if(cmfPages::getParam(3)) {
        $row = cmfRegister::getSql()->placeholder("SELECT t.uri AS tUri, y.uri FROM ?t y LEFT JOIN ?t t ON(y.type=t.id) WHERE y.uri=?", db_arenda_yachts, db_arenda_type, cmfPages::getParam(3))
    								    ->fetchAssoc();
    	if($row) {
    	    cmfRedirectSeo(cmfGetUrl('/arenda/yachts/', array($row['tUri'], $row['uri'])));
    	}
    }
    return 404;
}
list($id, $param1, $param2, $page) = $row;

cmfGlobal::set('$infoId', $id);
cmfGlobal::set('$param2', $param1);
cmfGlobal::set('$param2', $param2);
return $page;

?>
