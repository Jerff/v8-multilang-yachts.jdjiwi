<?php

class arenda_edit_controller extends driver_controller_edit_tree_lang {

    protected function init() {
        parent::init();
        $this->addModul('main', 'arenda_edit_modul');
        $this->addModul('lang', 'arenda_edit_lang_modul');

        // url
        $this->setSubmitUrl('/admin/arenda/edit/');
        $this->setCatalogUrl('/admin/arenda/');
    }

    public function viewSiteUrl() {
        return parent::viewSiteUrl('/arenda/', $this->viewSiteData('isUri'));
    }

    public function delete($id) {
        $id = parent::delete($id);
        cmfModulLoad('main_slider_edit_controller')->deleteSlider('arenda/edit', $id);
        cmfConfigArenda::delete($id);
        return $id;
    }

    public function getFlashParamList() {
        if (!$this->getId())
            return;

        $id = (int) $this->getId();
        $res = $this->getModul()->getDb()->getDataList(array('parent' => $id, 'OR', "path LIKE '%[$id]%'"));
        $list = array();
        foreach ($res as $k => $v) {
            $list[$v['parent']][$k] = array('name' => $v['name'],
                'image' => !empty($v['yachtsListImage']) ? '/' . cmfPathArenda . $v['yachtsListImage'] : '',
                'url' => '/' . $v['isUri'] . '/', cmfGetUrl('/arenda/', array($v['isUri'])));
        }
        return $list;
    }

}

?>