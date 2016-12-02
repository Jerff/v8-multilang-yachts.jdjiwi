<?php

cmfAjax::start();
$r = cmfRegister::getRequest();


$param = $r->getPost('param');
if($param and is_array($param)) {
	$sectionId = (int)$r->getPost('section');

	if($r->getPost('paramMainNew')) {	    if(false===($param = cmfCache::get('cmfSectionFilter Param'. $sectionId))) {
	    	$res = cmfRegister::getSql()->placeholder("SELECT id FROM ?t WHERE types=(SELECT type FROM ?t WHERE id=?) AND `select`='yes' AND visible='yes' ORDER BY pos", db_param, db_section, $sectionId)
										->fetchAssocAll();
			$query = $sep = '';
			foreach($res as $row) {
	            $param[$row['id']] = 0;
			}
	        cmfCache::set('cmfSectionFilter Param'. $sectionId, $param, array('paramList', 'section'. $sectionId));
		}
	}
	$brandId = array();
	$brand = $r->getPost('brand');
	if($brand and is_array($brand))
		foreach($brand as $k=>$v) {			$brandId[(int)$k] = (int)$k;		}

	$paramMainId = (int)$r->getPost('paramMainId');
	$paramMainValue = (int)$r->getPost('paramMainValue');

	list($brand, $paramSearch) = cmfSectionFilter($sectionId, $brandId, $paramMainId, $paramMainValue, $param);
} else exit;

foreach($paramSearch as $id=>$v) {
	ob_start(); ?>
<?=$v['name'] ?>
<div class="empty"></div>
<select name="param[<?=$id ?>]" id="param[<?=$id ?>]" onchange="cmfSectionFilterShange(this.form)">
<option></option>
<?  $k = (int)get($param, $id);
	foreach($v['param'] as $k2=>$v2) { ?>
<option value="<?=$k2 ?>" <?=$k==$k2 ? 'selected' : '' ?>><?=$v2 ?></option>
<? } ?>
</select>
<?
	$content = ob_get_clean();
	cmfAjax::get()->loadHTML('#select'. $id, $content);
}

?>