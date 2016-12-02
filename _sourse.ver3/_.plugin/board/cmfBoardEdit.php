<?php
cmfLoad('ajax/cmfMainAjaxSave');

class cmfBoardEdit extends cmfMainAjaxSave {

    function __construct($name = 'boardEdit', $formUrl = null, $func = null) {
        $this->set('$isUser', cmfRegister::getUser()->is());
        $formUrl = cmfControllerUrl . '/board/edit/?';
        $func = 'cmfAjaxSendForm';
        parent::__construct($formUrl, $name, $func);
    }

    protected function init() {
        $form = $this->getForm(1);
        $form->add('login', new cmfFormTextMain(array('empty')));

        $form->add('date', new cmfFormDateMain(null, null, array('name' => word('Дата показа'))));
        $form->add('dateEnd', new cmfFormDateMain());
        $form->select('dateEnd', date('Y-m-d H:i:s', time() + 12 * 30 * 24 * 60 * 60));

        $form->add('name', new cmfFormTextMain(array('max' => 255, 'name' => word('Заголовок'), '!empty')));
//        $form->add('phone', new cmfFormTextMain(array('max' => 255, 'name' => word('Номер телефона'), '!empty')));
        $form->add('contact', new cmfFormTextareaSmall(array('max' => 255, 'name' => word('Контакты'), '!empty')));
        $form->add('notice', new cmfFormTextareaMain(array('max' => 10000, 'name' => word('Текст объявления'), '!empty')));
        $form->add('price', new cmfFormTextRigthSmall(array('max' => 255, 'name' => word('Цена'), '!empty')));
        $form->add('currency', new cmfFormSelect(array('name' => word('Валюта'))));
        foreach (array('EURO', 'USD', 'RUR', 'UAH') as $value) {
            $form->addElement('currency', $value, $value);
        }

        $form->add('image', new cmfFormFileMain(array('path' => cmfPathBoard, 'fileSize' => 1, 'size' => array(1000, 1000))));

        $param = cmfRegister::getSql()->placeholder("SELECT p.id, p.value FROM ?t p LEFT JOIN ?t b ON(p.id=b.param) WHERE p.visible='yes' AND b.visible='yes' AND b.`group`='board' ORDER BY b.pos", db_param, db_param_group_notice)
                ->fetchAssocAll('id');
        $list = cmfRegister::getSql()->placeholder("SELECT id, lang, name FROM ?t WHERE id ?@", db_param_lang, array_keys($param))
                ->fetchAssocAll('id', 'lang');
        foreach ($param as $id => $row) {
            cmfLang::data($row, get($list, $id));
            $form->add('paramKey' . $id, new cmfFormSelectInt(array('name' => $row['name'])));
            foreach ($row['value'] = cmfString::unserialize($row['value']) as $key => $value) {
                $form->addElement('paramKey' . $id, '-1', word('Не выбрано'));
                $form->addElement('paramKey' . $id, $key, word($value));
            }
            $form->add('paramValue' . $id, new cmfFormTextSmall(array('max' => 255)));
            $param[$id] = $row;
        }
        $this->set('$param', $param);
    }

    public function loadData($row = null) {
        $this->set('$boardData', $row);
        $this->getForm()->selectAll($row);
        $param = cmfString::unserialize($row['param']);
        foreach ($this->get('$param') as $id => $row) {
            foreach (array('paramKey' . $id, 'paramValue' . $id) as $key) {
                $this->getForm()->select($key, get($param, $key));
            }
        }
    }

    public function viewImage() {
        $boardData = $this->get('$boardData');
        ?><span id="images-view" class="images-view"><?
        if (!empty($boardData['image'])) {
            $boardId = $boardData['id'];
            $this->viewHtmlImage($boardId, $boardData['image']);
        }
        ?></span><?
    }

    public function viewHtmlImage($boardId, $image) {
        foreach (cmfString::unserialize($image) as $key => $value) {
            if(empty($value))                continue;
            ?>
            <span>
                <a href="<?= cmfBaseImg . cmfPathBoard . $value ?>" class="fancybox">/<?= cmfPathBoard . $value ?></a>
                <span class="images-view-command" data-board="<?= $boardId ?>" data-key="<?= $key ?>">[удалить]</span>
            </span><?
        }
    }

    public function jsHtmlImage($boardId, $image) {
        ob_start();
        $this->viewHtmlImage($boardId, $image);
        cmfAjax::get()->html('#images-view', ob_get_clean());
//        cmfAjax::get()->script('cmf.form.images.command.init();');
//        cmfAjax::get()->script('$("a.fancybox").fancybox({});');
    }

    public function run() {
        if (empty($_POST['boardId']))
            return;
        $boardValue = $this->runStart();
        $boardData = $this->getForm()->handler();


        unset($boardValue['login'], $boardData['login']);
        $board = cmfRegister::getSql()->placeholder("SELECT * FROM ?t WHERE id=? AND user=?", db_board, $boardId = (int) $_POST['boardId'], cmfRegister::getUser()->getId())
                ->fetchAssoc();
        if (!$board)
            exit;
//        pre($boardValue, $send, $board);

        $email = $board['email'];

        $boardValue['update'] = time();
        $boardValue['moder'] = 'no';
        if (!empty($boardValue['image'])) {
            $images = array_merge(
                    cmfString::unserialize($board['image']), cmfString::unserialize($boardValue['image'])
            );
            foreach ($images as $key => $value) {
                if(empty($value)) {
                    unset($images[$key]);
                }
            }
            $boardValue['image'] = cmfString::serialize($images);
            $this->jsHtmlImage($boardId, $boardValue['image']);
            cmfAjax::get()->script('$(".images-form .images-board:not(:first)").remove();');
            cmfAjax::get()->script('cmf.form.images.init($(".images-form"));');
        }
        $param = cmfString::unserialize($board['param']);
        $isUpdate = false;
        foreach ($this->get('$param') as $k => $v) {
            if (isset($boardValue['paramKey' . $k])) {
                if ($boardValue['paramKey' . $k] == -1) {
                    $boardData['paramKey' . $k] = get($boardValue, 'paramValue' . $k, $param['paramValue' . $k]);
                }
            }
            foreach (array('paramKey' . $k, 'paramValue' . $k) as $key) {
                if (isset($boardValue[$key])) {
                    $isUpdate = true;
                    $param[$key] = $boardValue[$key];
                    unset($boardValue[$key]);
                }
            }
        }
        if ($isUpdate) {
            $boardValue['param'] = cmfString::serialize($param);
        }

        cmfRegister::getSql()->add(db_board, $boardValue, $boardId);

        $boardData['date'] .= ' - ' . $boardData['dateEnd'];

        $userMail = array();
        $userMail['boardUrl'] = cmfGetUrl('/board/item/', array($boardId));
        $userMail['editUrl'] = cmfProjectAdmin . 'board/edit/#&id=' . $boardId;
        $userMail['moderUrl'] = cmfProjectAdmin . 'board/edit/#&moder=1&id=' . $boardId;
        $userMail['name'] = $email;
        $userMail['data'] = cmfFormtaArray(
                $this->getForm()->processingName($boardData)
        );

        $cmfMail = new cmfMail();
        if (!empty($boardValue['image'])) {
            foreach (cmfString::unserialize($boardValue['image']) as $key => $value) {
                $cmfMail->addAttachment(cmfWWW . cmfPathBoard . $value, cmfWWW . cmfPathBoard . $value);
            }
        }
        $cmfMail->sendType('user', 'Доска объявлений: Редактировать объявление: Письмо менеджеру', $userMail);

        cmfAjax::get()->script($this->getForm()->jsUpdate());

        $this->runSaveOk();
        die();
    }

}
?>