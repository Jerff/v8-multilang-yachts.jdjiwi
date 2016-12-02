<?php

// обстрактный контроллер страницы редактирования
abstract class driver_controller_edit extends _interface_controller_siteUrl {

    // имя js модуля
    private $name = null;
    // модули контролера
    private $modul = array();
    // режим обновления
    private $update = false;
    private $updateList = array();
    private $url;

    function __construct($id = null) {
        $this->initRequest();
        if (!$id) {
            $id = $this->getId();
        }
        $this->setId(str_replace(array('"', "'"), '', $id));
        $this->init();
    }

    // инициализация данных в потомке
    protected function init() {

    }

    // очистка от ненужных данных (баз данных)
    public function free() {
        $modul = $this->getModulAll();
        if ($modul) {
            while (list(, $m) = each($modul))
                if ($m)
                    $m->free();
        }
        unset($this->updateList);
    }

    protected function setUpdate() {
        $this->update = true;
    }

    protected function setNoUpdate() {
        $this->runUpdateData();
        $this->update = false;
    }

    protected function runUpdateData() {
        foreach ($this->getModulAll() as $modul)
            if ($modul->getDb()) {
                $modul->getDb()->runUpdateData();
            }
    }

    protected function getUpdate() {
        return $this->update;
    }

    // получаем имя класса
    public function getName() {
        return get_class($this);
    }

    // html id места для отображения ошибок
    protected function getNameError() {
        return $this->getName() . '_error';
    }

    // ---------------  работа с объектами ---------------
    // добавить модуля (обновляемый/необновляемый) в контроллер
    protected function addModul($n, $m, $u = true) {
        $m = cmfModulLoad($m);
        if ($this->isChageIdName()) {
            $m->setIdName($this->getIdName());
        }
        $m->initModul();
        $this->modul[$n] = $m;
        if ($u)
            $this->updateList[$n] = $m;
    }

    protected function &getModul($n = 'main') {
        return $this->modul[$n];
    }

    protected function delModul($n) {
        unset($this->modul[$n], $this->updateList[$n]);
    }

    protected function &getModulAll() {
        if ($this->getUpdate()) {
            reset($this->updateList);
            return $this->updateList;
        } else {
            reset($this->modul);
            return $this->modul;
        }
    }

    // отдает списко ID объектов по условию WHERE
    public function getListId($where) {
        return $this->getModul()->getListId($where);
    }

    // ---------------  /работа с объектами ---------------
    // выбрать текущие данные
    public function &current() {
        $current = new cmfData();
        foreach ($this->getModulAll() as $n => $m)
            $current->$n = $m->current();
        return $current;
    }

    // --------------- работа с урлами ---------------
    // генерация строки get-запроса
    protected function requestUri($opt = null) {
        $get = $this->getRequest()->getGetAll();
        if (!is_null($opt))
            $get = array_merge($get, (array) $opt);
        return cmfRequest::viewParam($get);
    }

    protected function setUrl($name, $url) {
        $this->url[$name] = cmfGetAdminUrl($url);
    }

    public function getUrl($name, $opt = null) {
        return get($this->url, $name) . $this->requestUri($opt);
    }

    public function getMenuUrl($url) {
        $opt = array('parent' => $this->getId());
        return cmfGetAdminUrl($url) . $this->requestUri($opt);
    }

    public function getUrl2($name) {
        return get($this->url, $name);
    }

    public function setSubmitUrl($url) {
        $this->setUrl('submit', $url);
    }

    public function getSubmitUrl($opt = null) {
        return $this->getUrl('submit', $opt);
    }

    public function setCatalogUrl($url) {
        return $this->setUrl('catalog', $url);
    }

    public function getCatalogUrl($opt = null) {
        $opt['id'] = null;
        $opt['parentId'] = null;
        return $this->getUrl('catalog', $opt);
    }

    // --------------- /работа с урлами ---------------
    // загрузка объектов данными
    public function run() {
        $modul = $this->getModulAll();
        while (list(, $m) = each($modul)) {
            $m->run();
        }
    }

    // удалить данные объекта
    public function delete($list) {
        if ($list) {
            $list = (array) $list;
            $modul = $this->getModulAll();
            while (list(, $m) = each($modul))
                $m->delete($list);

            foreach ((array) $list as $id)
                cmfWysiwyng::delRecord($this->getWysiwyngPath(), $id);
        }
        return $list;
    }

    // обновление форм
    // проверяем есть ли ошибки интерпритации формы и если нет сохраняем, иначе - выводим ошибки
    protected function update() {
        $this->setUpdate();

        $arg = func_get_args();
        $save = get($arg, 0) === 'save';

        $response = $this->getResponse();

        // проверяем формы модулей на ошибки
        $isError = false;
        $modulAll = $this->getModulAll();
        while (list(, $modul) = each($modulAll)) {
            $modul->updateIsError($isError);
            if ($isError)
                break;
        }

        $js = '';
        if (!$isError) {
            // обновляем данные
            reset($modulAll);
            while (list(, $modul) = each($modulAll)) {
                $modul->updateOk();
            }

            //создание папки для записи
            if ($this->isNewRecord()) {
                cmfWysiwyng::addRecord($this->getWysiwyngPath(), $this->getId());
            }

            // сохранение - возвращется на страницу списка
            if ($save and $this->getId()) {
                $response->addRedirect($this->getCatalogUrl());
            }

            // если создали новый элемент перегружаем страницу
            if ($this->isNewRecord()) {
                $response->addRedirect($this->getSubmitUrl());
            }

            // открываем страницу сново
            if ($this->isReload()) {
                $response->addScript('cmfAjaxOpenUrl("' . $this->getSubmitUrl() . '");');
            }

            // отрисовываем страницу сново
            if ($this->isNewView()) {
                return;
            }

            reset($modulAll);
            while (list($name, $modul) = each($modulAll)) {
                $modul->run();
                $js .= $modul->getJsSetData();

                $file = $modul->getListFile();
                foreach ($file as $f) {
                    $js .= $this->getJsFile($name, $f);
                }
            }

            $this->updateSiteUrl();
        } else {
            // выводим пользователю ошибки форм
            reset($modulAll);
            while (list(, $modul) = each($modulAll)) {
                $modul->updateError();
                $modul->getUpdateError();
                $js .= $modul->getJsSetData(false);
            }
        }
        $response->addScript($js);
    }

    // загрузка файлов
    protected function getFileId($name, $element) {
        return $this->getName() . '_' . $this->getId() . '_' . $name . $element;
    }

    public function getImage($name, $element, $option = array(), $text = '(Добавить)') {
        $option['isImage'] = 1;
        $this->getFile($name, $element, $option, $text);
    }

    public function getImageMulti($name, $element, $option = array(), $text = '(Добавить)') {
        $option['isImage'] = 1;
        $option['isMulti'] = 1;
        $this->getFile($name, $element, $option, $text);
    }

    public function getImageView($name, $element, $option = array(), $text = '(Добавить)') {
        $option['isImageView'] = 1;
        $this->getImage($name, $element, $option, $text);
    }

    public function getFile($name, $element, $option = array(), $text = '(Добавить)') {
        $modul = $this->getModul($name);
        if (is_null($modul))
            return '';
        if (!$modul->getForm()->isObject($element))
            return '';

        $fileId = $this->getFileId($name, $element);
        $jsModul = $this->getModulName();
        $form = $modul->getForm();

        view_edit::getFile($this->getId(), $jsModul, $fileId, $form, $name, $element, $option, $text);
    }

    public function getJsFile($name, $element) {
        $modul = $this->getModul($name);
        if (is_null($modul))
            return '';
        if (!$modul->getForm()->isObject($element))
            return '';

        $fileId = $this->getFileId($name, $element);
        $jsModul = $this->getJsModulName();
        $form = $modul->getForm();

        $option = $this->getRequest()->getPost($fileId . '_option');
        if ($option)
            $option = unserialize($option);

        view_edit::getJsFile($this->getId(), $jsModul, $fileId, $form, $name, $element, $option);
    }

    protected function deleteFile($name, $element, $id, $key=-1) {
        if (!$this->getId()) {
            $this->setId($id);
        }
        $model = $this->getModul($name);
        if (is_null($model)) {
            exit;
        }

        $model->deleteFile($element, $key);
        $this->getResponse()->loadHTML($this->getFileId($name, $element), '');
    }

    //  --------------- вывод формы - старт и конце, вывод всех JavaScript функций учавствующий в обработке формы ---------------
    //
	protected function setModulName($n) {
        $this->name = $n;
    }

    protected function getModulName() {
        return $this->name;
    }

    protected function getJsModulName() {
        return $this->getRequest()->getPost('ajaxJsName');
    }

    public function htmlStartForm($jsModul = 'modul') {
        $this->getRequest()->resetGet();
        $this->setModulName($jsModul);
        $name = $this->getName();
        $error = $this->getNameError();
        $action = $this->getSubmitUrl();

        return view_edit::htmlStartForm($name, $error, $action, $jsModul);
    }

    public function htmlEndForm() {
        $jsModul = $this->getModulName();
        return view_edit::htmlEndForm($jsModul);
    }

    //  --------------- /вывод формы - стари и конце, вывод всех JavaScript функций учавствующий в обработке формы ---------------
    // добавление строк в список
    protected function &getNewLineData() {
        $data = array();
        $modulAll = $this->getModulAll();
        while (list($name, $modul) = each($modulAll)) {
            $data[$name] = $modul->getNewLineData();
        }
        return $data;
    }

    protected function saveLineOk($formName) {
        $modulAll = $this->getModulAll();
        $this->setId(0);
        while (list($name, $modul) = each($modulAll)) {
            $modul->saveLineOk(get($formName, $name));
        }
    }

    // функции для управления папками для визуального редактора
    public function getWysiwyngIsRecord($id) {
        return (bool) $this->getModul()->getDb()->getDataId($id);
    }

    public function getWysiwyngPath() {
        $wysiwing = new cmfWysiwyng();
        return $wysiwing->getRecordPath(get_class($this));
    }

    public function filterMenu2($header, $id, $name) {
        if ($data = cmfModulLoad($name)->getDataId($this->getFilter($id))) {
            return array('header' => $header,
                'name' => $data['name'],
                'url' => $this->getCatalogUrl());
        } else {
            return false;
        }
    }

    public function filterMenu3($header, $id, $name) {
        if ($data = cmfModulLoad($name)->getDataId($id)) {
            return array('header' => $header,
                'name' => get($data, 'name'),
                'url' => $this->getCatalogUrl());
        } else {
            return false;
        }
    }

    public function langViewTranslate($name) {
        if (!$this->getId())
            return;
        if (cmfLang::getFirst() == cmfLang::getId())
            return;
        static $data = null;
        if (!$data) {
            $data = $this->getModul('lang')->getDb()->langLoadData();
        }
        $content = get2($data, cmfLang::getFirst(), $name);
        view_lang::view($name, $content);
    }

}

?>
