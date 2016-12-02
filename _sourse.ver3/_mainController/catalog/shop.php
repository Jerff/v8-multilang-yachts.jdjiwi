<?php


cmfAjax::start();
$r = cmfRegister::getRequest();


$index = (int)$r->getPost('count');

$sql = cmfRegister::getSql();
$shop = $sql->placeholder("SELECT SQL_CALC_FOUND_ROWS p.id, IF(LENGTH(s.name), s.name, p.name) AS name, s.image, p.price, u.url FROM ?t s LEFT JOIN ?t p ON(s.product=p.id) LEFT JOIN ?t u ON(p.id=u.product) WHERE s.product=p.id AND u.page='/product/' AND p.id=u.product AND p.visible='yes' AND s.visible='yes' ORDER BY s.pos LIMIT ?i, ?i", db_shop, db_product, db_catalog_url, $index-1, $index)
			->fetchAssoc();
if(!$shop) exit;
$shop['name'] = htmlspecialchars($shop['name']);
$shop['image'] = cmfFilePathImage . $shop['image'];
$shop['url'] = cmfGeturl('/product/', array($shop['url']));;

$count = $sql->getFoundRows();
$i = 0;
ob_start();
?>
<a href="<?=$shop['url'] ?>"><img src="<?=$shop['image'] ?>" alt="<?=$shop['name'] ?>"></a>
<div class="nav"><a href="javascript:void(0);" onclick="cmfShangeMainImage(-1);"><</a><? for($i=1; $i<=$count and $i<7; $i++) {
	?><a href="javascript:void(0);" onclick="cmfShangeMainImage(<?=$i ?>);" <?= $i==$index? 'class="active"' : '' ?>><?=$i ?></a><?
} ?><? if($count>1) { ?><a href="javascript:void(0);" onclick="cmfShangeMainImage(-2);">></a><? } ?></div>
<script type="text/javascript">
document.countShopMain = <?=$index ?>;
</script>
<?
cmfAjax::get()->loadHTML('#indexShop', ob_get_clean());

?>