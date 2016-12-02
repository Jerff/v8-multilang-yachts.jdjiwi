<?php

$infoUri = cmfPages::getParam(2);



//pre(cmfPages::getParamAll());
if (!$infoUri)
    return 404;
$row = cmfRegister::getSql()->placeholder("SELECT id, param1, param2, page FROM ?t WHERE url=? AND part=? ", db_content_url, $infoUri, cmfPart)
        ->fetchRow();
if (!$row) {
    if (cmfPages::getParam(4)) {
        $row = cmfRegister::getSql()->placeholder("SELECT t.uri AS tUri, y.uri FROM ?t y LEFT JOIN ?t t ON(y.type=t.id) WHERE y.uri=?", db_arenda_yachts, db_arenda_type, cmfPages::getParam(4))
                ->fetchAssoc();
        if ($row) {
            cmfRedirectSeo(cmfGetUrl('/arenda/yachts/', array($row['tUri'], $row['uri'])));
        }
    }
    return 404;
}
list($id, $param1, $param2, $page) = $row;

cmfGlobal::set('$infoId', $id);
cmfGlobal::set('$param1', $param1);
cmfGlobal::set('$param2', $param2);
cmfGlobal::set('$pageId', cmfPages::getParam(8) ? (int)cmfPages::getParam(8) : 1);
return $page;
?>