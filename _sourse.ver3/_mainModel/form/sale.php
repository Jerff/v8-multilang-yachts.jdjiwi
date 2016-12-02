<?php

$sql = cmfRegister::getSql();
$this->assing2('saleUrl', cmfGetUrl('/sale/request/'));
$this->assing2('arendaUrl', cmfGetUrl('/arenda/request/'));

cmfGlobal::set('body', 'inquiry');
cmfLoad('request/cmfRequestSale');
$request = new cmfRequestSale();


switch (get($_GET, 'type')) {
    case 'brokerage':
        $yachtsId = (int) get($_GET, 'id');
        if (!$yachtsId)
            break;
        $yachts = $sql->placeholder("SELECT y.id, y.uri, y.param, t.uri AS tUri FROM ?t y LEFT JOIN ?t t ON(t.id=y.type) WHERE y.id=? AND y.visible='yes' AND t.visible='yes'", db_brokerage_yachts, db_brokerage_type, $yachtsId)
                ->fetchAssoc();
        if (!$yachts)
            break;
        $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id=?", db_brokerage_yachts_lang, $yachts['id'])
                ->fetchAssocAll('lang');
        cmfLang::data($yachts, $list);

        $url = cmfGetUrl('/brokerage/yachts/', array($yachts['tUri'], $yachts['uri']));
        $request->getForm(2)->select('yachts', "{$yachts['name']}\n{$url}");

        $this->assing('yachts', $yachts['name']);
        cmfLoad('catalog/function');
        $lengthValue = cmfParam::getParamId($lengthId = 3);
        $yearValue = cmfParam::getParamId($yearId = 19);
        $request->getForm(2)->select('length', cmfParam::selectParam($yachts['param'], $lengthId, $lengthValue));
        $request->getForm(2)->select('year', cmfParam::selectParam($yachts['param'], $yearId, $yearValue, false));
        break;

    case 'shipyards':
        $yachtsId = (int) get($_GET, 'id');
        if (!$yachtsId)
            break;
        $yachts = $sql->placeholder("SELECT y.id, y.uri, y.param, s.uri AS sUri FROM ?t y LEFT JOIN ?t s ON(s.id=y.shipyards) WHERE y.id=? AND y.visible='yes' AND s.visible='yes'", db_shipyards_yachts, db_shipyards, $yachtsId)
                ->fetchAssoc();
        if (!$yachts)
            break;
        $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id=?", db_shipyards_yachts_lang, $yachts['id'])
                ->fetchAssocAll('lang');
        cmfLang::data($yachts, $list);

        $url = cmfGetUrl('/shipyards/yachts/', array($yachts['sUri'], $yachts['uri']));
        $request->getForm(2)->select('yachts', "{$yachts['name']}\n{$url}");

        $this->assing('yachts', $yachts['name']);
        $this->assing2('shipyards', true);
        cmfLoad('catalog/function');
        $lengthValue = cmfParam::getParamId($lengthId = 3);
        $yearValue = cmfParam::getParamId($yearId = 19);
        $request->getForm(2)->select('length', cmfParam::selectParam($yachts['param'], $lengthId, $lengthValue));
        $request->getForm(2)->select('year', cmfParam::selectParam($yachts['param'], $yearId, $yearValue, false));
        break;

    default:
        break;
}

$this->assing('request', $request);
$this->assing('contact', $request->getForm(1));
$this->assing('data', $request->getForm(2));
?>