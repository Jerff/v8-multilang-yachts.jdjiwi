<?php

class param_edit_controller extends driver_controller_edit {

    protected function init() {
        parent::init();
        $this->addModul('main', 'param_edit_modul');
        $this->addModul('lang', 'param_edit_lang_modul');

        // url
        $this->setSubmitUrl('/admin/param/edit/');
        $this->setCatalogUrl('/admin/param/');

        $this->callFuncWriteAdd('paramUpdate|paramAdd|paramDelete');
    }

    public function filterMenu() {
        return parent::filterMenu2('Тип', 'group', 'param_group_edit_db');
    }

    public function delete($id) {
        parent::delete($id);
        //cmfModulLoad('product_param_db')->deleteParam($id);
        return $id;
    }

    protected function paramUpdate() {
        $this->getModul()->getDb()->paramUpdate($this->getId(), $this->getRequest()->getPost('paramId'), $this->getRequest()->getPost('paramValue'));
        $this->paramView();
    }

    protected function paramAdd() {
        if ($this->getModul()->getDb()->paramAdd($this->getId(), $this->getRequest()->getPost('paramValue'))) {
            $this->paramViewNew();
        }
        $this->paramView();
    }

    protected function paramDelete() {
        $this->getModul()->getDb()->paramDelete($this->getId(), $this->getRequest()->getPost('paramId'));
        $this->paramView();
        $this->paramViewNew();
    }

    protected function paramViewNew() {
        $this->getResponse()->addScript("
        	cmf.setValue('paramId', '');
        	cmf.setValue('paramValue', '');
        ");
    }

    protected function paramView() {
        $data = $this->getModul()->getDb()->runData();
        if (!empty($data['value'])) {
            $data = unserialize($data['value']);
        } else {
            $data = array();
        }
        $param = array();
        foreach ($data as $k => $v) {
            $param[$k . ' ' . cmfHtmlSpecialchars($v)] = cmfHtmlSpecialchars($v);
        }

        ob_start();
        ?>
        <select size="25" class="width99" onchange="cmf.pages.select(this.value);">
            <? foreach ($param as $k => $v) { ?>
                <option value="<?= $k ?>"><?= $v ?></option>
        <? } ?>
        </select><?
        $this->getResponse()->loadHTML('#paramView', ob_get_clean());
        if (cmfGlobal::is('$paramLog')) {
            $this->getResponse()->loadHTML('#paramLog', cmfGlobal::get('$paramLog'));
            $this->getResponse()->addScript("$('#paramLog').show();");
        } else {
            $this->getResponse()->addScript("$('#paramLog').hide();");
        }
    }

}
?>