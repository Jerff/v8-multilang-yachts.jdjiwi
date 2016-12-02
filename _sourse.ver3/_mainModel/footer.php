<?php


$main = cmfPages::isMain('/index/');
if($adress = cmfCache::get('_footer'. $main)) {
	list($adress, $footer, $copyright, $сounters, $mMenu) = $adress;
} else {

	$sql = cmfRegister::getSql();
	list($adress, $footer) = $sql->placeholder("SELECT adress, footer FROM ?t WHERE id='main' AND lang=? LIMIT 0,1", db_main_lang, cmfLang::getId())
							->fetchRow();
	list($copyright) = $sql->placeholder("SELECT content FROM ?t WHERE id='main' AND lang=?  LIMIT 0,1", db_seo_copyright, cmfLang::getId())
							->fetchRow();

	if($main) {
		$сounters = $sql->placeholder("SELECT id, counters FROM ?t WHERE main='no' AND `type`='footer' AND visible='yes' ORDER BY pos ", db_seo_counters)
							->fetchRowAll(0, 1);
	} else {
		$сounters = $sql->placeholder("SELECT id, counters FROM ?t WHERE `type`='footer' AND visible='yes' ORDER BY pos ", db_seo_counters)
							->fetchRowAll(0, 1);
	}
    $сounters = implode('', $сounters);

    $mMenu = cmfMenu::getFooter();
    end($mMenu);
    $mMenu[key($mMenu)]['last'] = true;

	cmfCache::set('_footer'. $main, array($adress, $footer, $copyright, $сounters, $mMenu), 'menu,seoCounters,seoCopyright');
}


$this->assing2('mMenu', $mMenu);
$this->assing2('footer', $footer);
$this->assing2('adress', $adress);
$this->assing('copyright', $copyright);
$this->assing('сounters', $сounters);

?>