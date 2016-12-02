<?php

$sql = cmfRegister::getSql();
$this->assing2('saleUrl', cmfGetUrl('/sale/request/'));
$this->assing2('arendaUrl', cmfGetUrl('/arenda/request/'));

cmfGlobal::set('body', 'inquiry');
cmfLoad('request/cmfRequestArenda');
$request = new cmfRequestArenda();


switch (get($_GET, 'type')) {
    case 'arenda':
        $yachtsId = (int) get($_GET, 'id');
        if (!$yachtsId)
            break;
        $yachts = $sql->placeholder("SELECT y.id, y.uri, y.param, t.uri AS tUri FROM ?t y LEFT JOIN ?t t ON(t.id=y.type) WHERE y.id=? AND y.visible='yes' AND t.visible='yes'", db_arenda_yachts, db_arenda_type, $yachtsId)
                ->fetchAssoc();
        if (!$yachts)
            break;
        $list = $sql->placeholder("SELECT id, lang, name FROM ?t WHERE id=?", db_arenda_yachts_lang, $yachts['id'])
                ->fetchAssocAll('lang');
        cmfLang::data($yachts, $list);
        $url = cmfGetUrl('/arenda/yachts/', array($yachts['tUri'], $yachts['uri']));
        $request->getForm(2)->select('yachts', "{$yachts['name']}\n{$url}");

        $this->assing('yachts', $yachts['name']);
        cmfLoad('catalog/function');
        $lengthValue = cmfParam::getParamId($lengthId = 3);
        $request->getForm(2)->select('length', cmfParam::selectParam($yachts['param'], $lengthId, $lengthValue));
        $request->getForm(2)->select('typeYacht', $yachts['type']);
        break;

    default:
        break;
}


$this->assing('request', $request);
$this->assing('contact', $request->getForm(1));
$this->assing('data', $request->getForm(2));
?>