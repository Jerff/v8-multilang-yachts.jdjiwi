<?php

cmfDebug::destroy();
$arendaUri = cmfPages::getParam(1);
if(!$arendaUri) exit;
$sql = cmfRegister::getSql();
if($arendaUri==='charter') {
                ->fetchRow(0);
                ->fetchRow(0);
if(!$flashXml) exit;
$this->assing('flashXml', $flashXml);

?>