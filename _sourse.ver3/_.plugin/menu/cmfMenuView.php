<?php

class cmfMenuView {

    static public function viewSubMenu($header, $menu) {
        $index = cmfGetUrl('/index/');
        ?>
		<div xmlns:v="http://rdf.data-vocabulary.org/#">
		<span class="B_crumbBox">
            <span class="B_firstCrumb"><span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" class="B_homeCrumb" href="<?= $index ?>" title="<?= word('Вернуться на главную') ?>">NSK-Yachts</a></span></span>
            <? foreach ($menu as $n => $u) { ?>
                <? if ($u) { ?>
                    → <span typeof="v:Breadcrumb"><a rel="v:url" property="v:title" class="B_crumb" href="<?= $u ?>" title="<?= cmfString::specialchars($n) ?>"><?= $n ?></a></span>
                <? } else { ?>
                    → <span class="B_lastCrumb"><span class="B_currentCrumb"><?= $n ?></span></span>
                <? } ?>
            <? } ?>            
        </span>
		</div><?
    }

}
    ?>