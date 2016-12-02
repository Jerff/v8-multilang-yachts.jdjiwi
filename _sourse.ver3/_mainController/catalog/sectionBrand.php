<?php


cmfAjax::start();
$r = cmfRegister::getRequest();

cmfLoad('catalog/function');
$sectionId = $r->getPost('section');

if(false===($_brand = cmfCache::get('sectionBrand'. $sectionId))) {

	if($sectionId) {
		$section = cmfRegister::getSql()->placeholder("SELECT path FROM ?t WHERE id=? AND isMenu='yes' AND isVisible='yes'", db_section, $sectionId)
										->fetchAssoc();
		$_brand = cmfRegister::getSql()->placeholder("SELECT id, name FROM ?t WHERE id IN(SELECT brand FROM ?t WHERE section IN(SELECT id FROM ?t WHERE id=? OR path LIKE '?s%' AND isMenu='yes' AND isVisible='yes') AND visible='yes' GROUP BY brand) AND visible='yes' ORDER BY `pos`", db_brand, db_product, db_section, $sectionId, $section['path'] ."[$sectionId]")
										->fetchRowAll(0, 1);

	} else {
		$section = array();
		$_brand = cmfRegister::getSql()->placeholder("SELECT id, name FROM ?t WHERE id IN(SELECT brand FROM ?t WHERE section IN(SELECT id FROM ?t WHERE isMenu='yes' AND isVisible='yes') AND visible='yes' GROUP BY brand) AND visible='yes' ORDER BY `pos`", db_brand, db_product, db_section)
										->fetchRowAll(0, 1);
	}
	cmfCache::set('sectionBrand'. $sectionId, $_brand, array('section'. $sectionId));
}

ob_start();
foreach($_brand as $k=>$v) {
	?><div class="check fastSearch1 cmfHide brandCheckbox"><label><input type="checkbox" id="brand1[<?=$k ?>]" name="brand1[<?=$k ?>]" value="<?=$k ?>" onclick="cmf.getId('checkBrandAll').checked=false;"><?=$v ?></label></div><?
}
$content = cmfToJsString(ob_get_clean());


if($r->getPost('main')) {
    cmfAjax::get()->addScript("
$('.brandCheckbox', $('#mainSearchText')).remove();
$('.brandCheckboxAll').before('$content');
cmfSelectBrandAll();
cmfShowSearch();
    ");

} else {
	ob_start();
	?>Производитель
<select name="brand"><option></option>
<? foreach($_brand as $k=>$v) { ?>
<option value="<?=$k ?>"><?=$v ?></option>
<? } ?>
</select>
<script type="text/javascript">
$('.brandCheckbox', $('.checkBrand2')).remove();
$(".brandCheckboxAll").before("<?=$content ?>");
cmfSelectBrandAll();
if(!cmfIsHide($('.brandCheckboxAll', $('.checkBrand2')))) {
	cmfShowSearch();
}
</script>
<?
	cmfAjax::get()->loadHTML('#brandList1', ob_get_clean());

}

?>