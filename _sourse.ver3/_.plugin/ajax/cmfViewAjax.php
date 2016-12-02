<?php

class cmfViewAjax {

    static public function startForm(&$form, $style = '', $name = '') {
        ?>
        <div id="<?= $form->getIdForm() ?>FormDiv"><a name="<?= $form->getIdHash() ?>"></a>
            <form id="<?= $form->getIdForm() ?>" action="" name="<?= $form->getIdForm() ?>" enctype="multipart/form-data" method="post" <?= $style ?>>
                <input type="hidden" name="lang" value="<?= cmfLang::getId() ?>">
                <script type="text/javascript">
                    $('#<?= $form->getIdForm() ?>').submit(function() {
                        _gaq.push(['_trackEvent', 'Отправка формы', 'Отправка формы \"<?= $name; ?>\"']);
                        return cmf.ajax.sendForm('<?= $form->getAjaxUrl() ?>', cmf.getId('<?= $form->getIdForm() ?>'));
                    });
                </script>
                <?
            }

            static public function onsubmit(&$form) {
                ?>return <?= $form->getFunc() ?>('<?= $form->getAjaxUrl() ?>', cmf.getId('<?= $form->getIdForm() ?>'));<?
    }

    static public function formError(&$form) {
                ?>
                <p class="text cmfHide" id="<?= $form->getIdForm() ?>Error"><b class="errorDiv2">Форма не отправлена!</b></p>
                <?
            }

            static public function formSave(&$form) {
                ?>
                <p class="cmfHide" id="<?= $form->getIdForm() ?>Save"><b class="errorDiv3">Данные сохранены</b></p>
                <?
            }

            static public function endForm(&$form) {
                ?>
            </form></div>
        <?
    }

}
?>