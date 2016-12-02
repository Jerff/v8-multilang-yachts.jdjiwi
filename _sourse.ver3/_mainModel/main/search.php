<?php

cmfLoad('search/cmfSelectYachts');
$post = cmfGlobal::get('$post');

list($mSType, $mShipyard, $sYachts) = cmfSelectYachts::selectSale();
$this->assing('mSType', $mSType);
$this->assing('mShipyard', $mShipyard);
$this->assing('sYachts', $sYachts);

list($mAType, $mArenda, $aYachts) = cmfSelectYachts::selectArenda();
$this->assing('mAType', $mAType);
$this->assing('mArenda', $mArenda);
$this->assing('aYachts', $aYachts);

$minAPrice = minMaxValue($post['minAPrice'], $aYachts['min(price)'], $aYachts['max(price)'], $aYachts['min(price)']);
$maxAPrice = minMaxValue($post['maxAPrice'], $aYachts['min(price)'], $aYachts['max(price)'], $aYachts['max(price)']);
$minSPrice = minMaxValue($post['minSPrice'], $sYachts['min(price)'], $sYachts['max(price)'], $sYachts['min(price)']);
$maxSPrice = minMaxValue($post['maxSPrice'], $sYachts['min(price)'], $sYachts['max(price)'], $sYachts['max(price)']);


$minASize = minMaxValue($post['minASize'], $aYachts['min(length)'], $aYachts['max(length)'], $aYachts['min(length)']);
$maxASize = minMaxValue($post['maxASize'], $aYachts['min(length)'], $aYachts['max(length)'], $aYachts['max(length)']);
$minSSize = minMaxValue($post['minSSize'], $sYachts['min(length)'], $sYachts['max(length)'], $sYachts['min(length)']);
$maxSSize = minMaxValue($post['maxSSize'], $sYachts['min(length)'], $sYachts['max(length)'], $sYachts['max(length)']);



$this->assing2('type', get($post, 'type', cmfMenu::getRequest()));
$this->assing2('isArenda', 'arenda' == get($post, 'type', cmfMenu::getRequest()));
$this->assing2('isSale', 'sale' == get($post, 'type', cmfMenu::getRequest()));
$this->assing2('searchUrl', cmfGetUrl('/sale/new/search/result/', array('new')));

$this->assing('minAPrice', $minAPrice);
$this->assing('maxAPrice', $maxAPrice);
$this->assing('minSPrice', $minSPrice);
$this->assing('maxSPrice', $maxSPrice);

$this->assing('minASize', $minASize);
$this->assing('maxASize', $maxASize);
$this->assing('minSSize', $minSSize);
$this->assing('maxSSize', $maxSSize);


$this->assing('post', $post);

function minMaxValue($value, $min, $max, $default) {
    return is_null($value) ? $default : ($value < $min ? $min : ($value > $max ? $max : $value));
}
?>