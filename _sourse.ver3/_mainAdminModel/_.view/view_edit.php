<?php

class view_edit {

    static public function getFile($id, $jsModul, $fileId, &$form, $name, $element, $option = null, $text) {
        $file = $form->getValue($element);

        $content = '<span id="' . $fileId . '_file">';
        $content .= $form->html($element, 'style="width:230px;"');
        $content .= '</span>';
        $content .= '&nbsp;&nbsp;' . cmfAdminView::onclickType2("{$jsModul}.submit();", $text) . '&nbsp;&nbsp;';

        $content .= '<span id="' . $fileId . '" class="button">';
        $content .= self::getFileView($id, $jsModul, $form, $name, $element, $option);
        $content .= '</span>';
        echo $content . '<input name="' . $fileId . '_option" type="hidden" value="' . htmlspecialchars(serialize($option)) . '">';
    }

    static private function getFileView($id, $jsModul, &$form, $name, $element, $option) {
        $content = '';
        if ($file = $form->getValue($element)) {
            $isImage = isset($option['isImage']);
            $isImageView = isset($option['isImageView']);
            if ($option['isMulti']) {
                foreach (cmfString::unserialize($file) as $key => $file) {
                    if(empty($file)) continue;
                    $path = cmfBaseUrl . '/' . $form->get($element)->getPath() . $file;
                    $content .= '<div class="empty"></div><div class="images-multi-command">';
                    if ($isImage) {
                        if ($isImageView) {
                            $content .= '<a href="' . $path . '" target="_blank" class="button fancybox" rel="group"><img src="' . $path . '" border="0" hspace="5" vspace="5" align="top" width="100px"></a>';
                        } else {
                            $content .= '<a href="' . $path . '" target="_blank" class="button fancybox" rel="group">' . $file . '</a>';
                        }
                    } else {
                        $content .= '<a href="' . $path . '" target="_blank">' . $file . '</a>';
                    }
                    $content .= '&nbsp;&nbsp;' . self::getFileCommand($id, $jsModul, $name, $element, $key);
                    $content .= '</div>';
                }
            } else {

                $path = cmfBaseUrl . '/' . $form->get($element)->getPath() . $file;
                if ($isImage) {
                    if ($isImageView) {
                        $content .= '<a href="' . $path . '" target="_blank" class="button fancybox" rel="group"><img src="' . $path . '" border="0" hspace="5" vspace="5" align="top" width="100px"></a>';
                    } else {
                        $content .= '<a href="' . $path . '" target="_blank" class="button fancybox" rel="group">' . $file . '</a>';
                    }
                } else {
                    $content .= '<a href="' . $path . '" target="_blank">' . $file . '</a>';
                }
                $content .= '&nbsp;&nbsp;' . self::getFileCommand($id, $jsModul, $name, $element);
            }
        }
        return $content;
    }

    static public function getJsFile($id, $jsModul, $fileId, &$form, $name, $element, $option) {
        $response = cmfAjax::get();


        $content = $form->html($element, 'style="width:200px;"');
        $response->loadHTML($fileId . '_file', $content);

        $content = self::getFileView($id, $jsModul, $form, $name, $element, $option);
        $response->loadHTML($fileId, $content);
    }

    static protected function getFileCommand($id, $jsModul, $name, $element, $key=-1) {
        return cmfAdminView::onclickType1("if(confirm('Удалить?')) {$jsModul}.ajax('deleteFile', '$name', '$element', '$id', '$key');", '[Удалить]');
    }

    //  --------------- вывод формы - старт и конце, вывод всех JavaScript функций учавствующий в обработке формы ---------------
    //
	static public function htmlStartForm($name, $error, $action, $jsModul) {
        $url = cmfProjectAdmin;
        return <<<HTML
<span id="{$error}" class="errorForm"></span>
<form name="form_{$name}_{$jsModul}" id="form_{$name}_{$jsModul}" action="{$url}" method="post" onsubmit="return {$jsModul}.submit();" enctype="multipart/form-data">
<script language="JavaScript">
{$jsModul} = new cmfController();
{$jsModul}.setUrl('{$action}');
{$jsModul}.setName('{$name}');
{$jsModul}.setJsName('{$jsModul}');
</script>
HTML;
    }

    static public function htmlEndForm($jsModul) {
        return '</form>';
    }

    //  --------------- /вывод формы - стари и конце, вывод всех JavaScript функций учавствующий в обработке формы ---------------
}

?>
