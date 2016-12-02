<?php


abstract class driver_controller_list_all_lang extends driver_controller_list_all {

    public function langViewTranslate($name) {
        if(!$this->getId()) return;
        if(cmfLang::getFirst()==cmfLang::getId()) return;
        static $data = null;
        if(!$data) {
            $data = $this->getModul('lang')->getDb()->langLoadData($this->getDataId());
        }
        $content = get3($data, $this->getId(), cmfLang::getFirst(), $name);
        view_lang::viewList($name, $this->getId(), $content);
    }

}

?>
