<?php

class cmfPaginationView {

    static public function view($page, $class = '') {
        ?>
        <ul class="pages <?= $class ?>">
            <li><?= word('Страницы') ?>:</li>

            <? if ($page['prev']) { ?>
                <li><a href="<?= $page['prev']['url'] ?>">«</a></li>
            <? } else { ?>

            <? } ?>

            <? foreach ($page['list'] as $k => $v) { ?>
                <? if (isset($v['is'])) {
                    ?><li id="list<?= $k ?>"><a href="<?= $v['url'] ?>" class="act"><?= $v['name'] ?></a></li><?
            } else {
                    ?><li id="list<?= $k ?>"><a href="<?= $v['url'] ?>"><?= $v['name'] ?></a></li><? }
                ?>
            <? } ?>

            <? if ($page['next']) { ?>
                <li><a href="<?= $page['next']['url'] ?>">»</a></li>
            <? } else { ?>
            <? } ?>
        </ul>
    <?
    }

    static public function view2($page, $class = '') {
        ?>
        <div class="page-navigation">
            <? if ($page['prev']) { ?>
                <a href="<?= $page['prev']['url'] ?>" class="page-numbers">«</a>
            <? } else { ?>

            <? } ?>

            <? foreach ($page['list'] as $k => $v) { ?>
                <? if (isset($v['is'])) {
                    ?><span class="page-numbers current"><?= $v['name'] ?></span><?
            } else {
                    ?><a href="<?= $v['url'] ?>" class="page-numbers"><?= $v['name'] ?></a><? }
            ?>
            <? } ?>

            <? if ($page['next']) { ?>
                <a href="<?= $page['next']['url'] ?>" class="page-numbers">»</a>
        <? } else { ?>
        <? } ?>
        </div>
    <?
    }

}
?>
