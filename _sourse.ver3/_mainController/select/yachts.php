<?php


cmfAjax::start();
$r = cmfregister::getRequest();


$post = array(
    'type' => get($_POST, 'type'),
    'aType' => get($_POST, 'aType'),
    'sType' => get($_POST, 'sType'),
    'minAPrice' => (int) get($_POST, 'minAPrice'),
    'maxAPrice' => (int) get($_POST, 'maxAPrice'),
    'minSPrice' => (int) get($_POST, 'minSPrice'),
    'maxSPrice' => (int) get($_POST, 'maxSPrice'),
    'minASize' => (int) get($_POST, 'minASize'),
    'maxASize' => (int) get($_POST, 'maxASize'),
    'minSSize' => (int) get($_POST, 'minSSize'),
    'maxSSize' => (int) get($_POST, 'maxSSize'),
    'charter' => (int) get($_POST, 'charter'),
    'shipyard' => urldecode(get($_POST, 'shipyard'))
);
if ($post['type'] != 'sale' and $post['type'] != 'arenda')
    return 1;
if($post['aType']) {
    $post['aType'] = array_map('ceil', $post['aType']);
    $post['aType'] = array_combine($post['aType'], $post['aType']);
}
if($post['sType']) {
    $post['sType'] = array_map('urldecode', $post['sType']);
    $post['sType'] = array_combine($post['sType'], $post['sType']);
}

cmfGlobal::set('$post', $post);

cmfLoad('search/cmfSelectYachts');
list($mSType, $mShipyard, $sYachts) = cmfSelectYachts::selectSale();
list($mAType, $mArenda, $aYachts) = cmfSelectYachts::selectArenda();

$minAPrice = minMaxValue($post['minAPrice'], $aYachts['min(price)'], $aYachts['max(price)'], $aYachts['min(price)']);
$maxAPrice = minMaxValue($post['maxAPrice'], $aYachts['min(price)'], $aYachts['max(price)'], $aYachts['max(price)']);
$minSPrice = minMaxValue($post['minSPrice'], $sYachts['min(price)'], $sYachts['max(price)'], $sYachts['min(price)']);
$maxSPrice = minMaxValue($post['maxSPrice'], $sYachts['min(price)'], $sYachts['max(price)'], $sYachts['max(price)']);


$minASize = minMaxValue($post['minASize'], $aYachts['min(length)'], $aYachts['max(length)'], $aYachts['min(length)']);
$maxASize = minMaxValue($post['maxASize'], $aYachts['min(length)'], $aYachts['max(length)'], $aYachts['max(length)']);
$minSSize = minMaxValue($post['minSSize'], $sYachts['min(length)'], $sYachts['max(length)'], $sYachts['min(length)']);
$maxSSize = minMaxValue($post['maxSSize'], $sYachts['min(length)'], $sYachts['max(length)'], $sYachts['max(length)']);

//sleep(10);
//cmfAjax::get()->addScript("
//alert($('#selectTime').val());
//alert('{$_POST['selectTime']}');
//");
cmfAjax::get()->addScript("
if('{$_POST['selectTime']}'==$('#selectTime').val()) {
    $('#minAPrice').val('{$minAPrice}').data({min: {$aYachts['min(price)']}}).data({max: {$aYachts['max(price)']}});
    $('#maxAPrice').val('{$maxAPrice}').data({min: {$aYachts['min(price)']}}).data({max: {$aYachts['max(price)']}});
    $('#minSPrice').val('{$minSPrice}').data({min: {$sYachts['min(price)']}}).data({max: {$sYachts['max(price)']}});
    $('#maxSPrice').val('{$maxSPrice}').data({min: {$sYachts['min(price)']}}).data({max: {$sYachts['max(price)']}});

    $('#minASize').val('{$minASize}').data({min: {$aYachts['min(length)']}}).data({max: {$aYachts['max(length)']}});
    $('#maxASize').val('{$maxASize}').data({min: {$aYachts['min(length)']}}).data({max: {$aYachts['max(length)']}});
    $('#minSSize').val('{$minSSize}').data({min: {$sYachts['min(length)']}}).data({max: {$sYachts['max(length)']}});
    $('#maxSSize').val('{$maxSSize}').data({min: {$sYachts['min(length)']}}).data({max: {$sYachts['max(length)']}});

    initSearchSliders();
}
");
//pre(cmfAjax::get());

function minMaxValue($value, $min, $max, $default) {
    return is_null($value) ? $default : ($value < $min ? $min : ($value > $max ? $max : $value));
}
?>