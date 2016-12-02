<?php



cmfLoad('catalog/function');
$infoId = cmfGlobal::get('$infoId');
$pageId = cmfGlobal::get('$pageId');
$sql = cmfRegister::getSql();
$limit = cmfConfig::get('site', 'boardLimit');
$offset = ($pageId - 1) * $limit;
if ($offset > 3000)
    return 404;
$userId = cmfRegister::getUser()->getId();
$res = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS id, `update`, user, date, name, image, param, visible, moder, data, price, currency FROM ?t WHERE (`dateEnd`>? AND visible='yes') OR user=?  ORDER BY `main`, date DESC LIMIT ?i, ?i", db_board, date('Y-m-d H:i:s'), $userId, $offset, $limit)
        ->fetchAssocAll('id');
if (!empty($res)) {
    $count = $sql->getFoundRows();

    $paramId = cmfConfig::get('site', 'boardParam');
    $paramValue = cmfParam::getParamId($paramId);
    $param2Id = cmfConfig::get('site', 'boardParam2');
    $paramValue2 = cmfParam::getParamId($param2Id);

    $mBoard = array();
    foreach ($res as $id => $row) {
        $row2 = $row;
        $status = array();
        if ($row['visible'] === 'no' and $row['moder'] === 'no') {
            $status[] = $row['moder'] === 'no' ? word('Не модерировано') : word('Модерировано');
        } else {
            if ($row['visible'] === 'no') {
                $status[] = word('Отключен');
            }
            $status[] = $row['moder'] === 'no' ? word('Не модерировано') : word('Модерировано');
        }
        $status = $row['user'] == $userId ? implode(', ', $status) : null;
        $isModer = ($row['visible'] === 'yes' or $row['moder'] === 'yes');
        if ($isModer and cmfRegister::getUser()->getId() != $row['user'] and !empty($board['data'])) {
//            $row = cmfString::unserialize($row['data']);
        }

        $param = array();
        $data = cmfString::unserialize($row['param']);
        foreach ($data as $key => $value) {
            if (strpos($key, 'paramKey') !== false) {
                $param[$key = str_replace('paramKey', '', $key)] = $value;
                $param['value-' . $key] = get($data, 'paramValue' . $key);
            }
        }
        $param = cmfString::serialize($param);

        $mBoard[$id] = array(
            'date' => date('d.m.Y', strtotime($row['date'])),
            'name' => $row['name'],
            'param' => cmfParam::selectParam($param, $paramId, $paramValue, false),
            'param2' => cmfParam::selectParam($param, $param2Id, $paramValue2, true),
            'title' => htmlspecialchars($row['name']),
            'status' => $status,
            'price' => cmfPrice::view($row['price'], $row['currency']),
            'url' => cmfGetUrl('/board/item/', array($id))
        );
        foreach (cmfString::unserialize($row['image']) as $key=>$value) {
            if(empty($value)) continue;
            $mBoard[$id]['image'] = cmfImage::preview(cmfBaseImg . cmfPathBoard . $value, 250, 130 , $row2['name'], 'board'.$key, $row['id'], $row2['update']);
            list(,,, $mBoard[$id]['width']) = getimagesize($mBoard[$id]['image']);
            break;
        }
    }

    $mPageUrl = cmfPagination::generate($pageId, $count, $limit, cmfConfig::get('site', 'boardPages'), create_function('&$page, $k, $v', '
    	$page[$k]["url"] = $k==1 ? cmfGetUrl("/board/") : cmfGetUrl("/board/page/", array($k));'));
    $this->assing('mBoard', $mBoard);
    if ($mPageUrl)
        $this->assing('mPageUrl', $mPageUrl);

    cmfGlobal::set('body', 'rent');
    $this->setTeplates('main.yachts.php');
}
?>