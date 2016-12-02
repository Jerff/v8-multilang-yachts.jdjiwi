<?php


abstract class driver_controller_gallery_edit_lang extends driver_controller_gallery_edit {

    public function langViewTranslate($name) {
        if(!$this->getId()) return;
        if(cmfLang::getFirst()==cmfLang::getId()) return;
        static $data = null;
        if(!$data) {
            $data = $this->getModul('lang')->getDb()->langLoadData();
        }
        $content = get2($data, cmfLang::getFirst(), $name);
        view_lang::view($name, $content);
    }

}

?>
