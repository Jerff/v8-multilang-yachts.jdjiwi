<?php

class _lang_word_controller extends driver_controller_list_all {

    protected function init() {
        parent::init();
        $this->addModul('main', '_lang_word_modul');

        // url
        $this->setSubmitUrl('/admin/lang/word/');
        $this->callFuncWriteAdd('newLine');
    }

    public function delete($id) {
        parent::deleteList($id);
        return parent::delete($id);
    }

    protected function getLimit() {
        $limit = $this->getFilter('limit');
        if ($limit === 'all')
            return 100;
        else
            return (int) $limit;
    }

}

?>