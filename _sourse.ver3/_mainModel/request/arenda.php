<?php


$sql = cmfRegister::getSql();
list($header, $content, $title, $keywords, $description) = $sql->placeholder("SELECT header, content, title, keywords, description FROM ?t WHERE id='request/arenda' LIMIT 0,1", db_main)
                   ->fetchRow();
$this->assing('header', $header);
$this->assing('content', $content);
cmfMenu::setHeader($header);
cmfMenu::setRequest('arenda');
cmfMenu::add($header, cmfGetUrl('/request/arenda/'));

cmfSeo::set('title', $title);
cmfSeo::set('keywords', $keywords);
cmfSeo::set('description', $description);

$this->assing2('saleUrl', cmfGetUrl('/request/sale/'));
$this->assing2('arendaUrl', cmfGetUrl('/request/arenda/'));


cmfLoad('request/cmfRequestArenda');
$request = new cmfRequestArenda();


switch(get($_GET, 'type')) {
    case 'arenda':
        $yachtsId = (int)get($_GET, 'id');
        if(!$yachtsId) break;
        $yachts = $sql->placeholder("SELECT y.*, t.uri AS tUri FROM ?t y LEFT JOIN ?t t ON(t.id=y.type) WHERE y.id=? AND y.visible='yes' AND t.visible='yes'", db_arenda_yachts, db_arenda_type, $yachtsId)
                                ->fetchAssoc();
        if(!$yachts) break;
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