<?php

class menu_pages_controller extends driver_controller_list_all {

    protected function init() {
        parent::init();
        $this->addModul('main', 'menu_pages_modul');

        // url
        $this->setSubmitUrl('/admin/menu/pages/');

        $this->callFuncWriteAdd('newLine');
    }

    public function delete($id) {
        parent::deleteList($id);
        return parent::delete($id);
    }

}

?>