<?php

class view_lang extends view_list {

    static public function view($name, $content) {
        ?>
        <div id="langView<?= $name ?>" class="langView">
            <span class="cursor">Показать оригинал</span>
            <div class="cmfHide"><code><?= nl2br(htmlspecialchars($content)) ?></code></div>
        </div>
        <script language="JavaScript">
            //$('#langView<?= $name ?>>div').resizable({animate: true});
            $('#langView<?= $name ?> .cursor').click(function() {
                cmf.style.hideShow('#langView<?= $name ?>>div');
            });
        </script>
    <?
    }

    static public function viewList($name, $id, $content) {
        ?>
        <div id="langView<?=$id ?>_<?= $name ?>" class="langView">
            <span class="cursor">Показать оригинал</span>
            <div class="cmfHide"><code><?= nl2br(htmlspecialchars($content)) ?></code></div>
        </div>
        <script language="JavaScript">
            //$('#langView<?= $name ?>>div').resizable({animate: true});
            $('#langView<?=$id ?>_<?= $name ?> .cursor').click(function() {
                cmf.style.hideShow('#langView<?=$id ?>_<?= $name ?>>div');
            });
        </script>
    <?
    }

}
?>
