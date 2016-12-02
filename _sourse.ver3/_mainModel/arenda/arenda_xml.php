<?php

cmfDebug::destroy();
$arendaUri = cmfPages::getParam(1);
if(!$arendaUri) exit;
$sql = cmfRegister::getSql();
if($arendaUri==='charter') {    $flashXml = $sql->placeholder("SELECT flashXml FROM ?t WHERE id='charter'", db_main)
                ->fetchRow(0);} else {    $flashXml = $sql->placeholder("SELECT flashXml FROM ?t WHERE isUri=? AND visible='yes'", db_arenda, $arendaUri)
                ->fetchRow(0);}
if(!$flashXml) exit;
$this->assing('flashXml', $flashXml);

?>