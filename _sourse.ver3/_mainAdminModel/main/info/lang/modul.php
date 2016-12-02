<?php


class main_info_lang_modul extends driver_modul_edit {

    const form = 'isContact|isHeader|isFooter|isFlashMap|isImage|isNotice|isContent|isTitle|isCoogleMap|isYandexMap';

    protected function init() {
        parent::init();

        $this->setDb('main_info_lang_db');

        // формы
        $form = $this->getForm();

        $isForm = array();
        foreach(explode('|', self::form) as $k) {
            $isForm[$k] = false;
        }
        switch($this->getId()) {
            case 'article':
            case 'news':
            case 'partners':
            case 'clients':
            case 'search':
                $isForm['isHeader'] = $isForm['isTitle'] = true;
                break;

            case 'brokerage':
                $isForm['isContent'] = $isForm['isNotice'] = $isForm['isHeader'] = $isForm['isTitle'] = true;
                break;

            case 'reviews':
                $isForm['isContent'] = $isForm['isHeader'] = $isForm['isTitle'] = true;
                break;

            case 'contact':
                $isForm['isContent'] = $isForm['isTitle'] = true;
                break;

            case 'shipyards/search':
                $isForm['isHeader'] = $isForm['isNotice'] = $isForm['isContent'] = $isForm['isTitle'] = true;
                break;

            case 'main':
                $isForm['isHeader'] = $isForm['isFooter'] = $isForm['isContent'] = $isForm['isContact'] = $isForm['isTitle'] = true;
                break;

            case 'sale/new':
            case 'sale/megayachty':
            case 'request/sale':
            case 'shipyards':
            case 'shipyards/search':
            case 'shipyards/search/result':
            case 'brokerage':

            case 'arenda/info':
            case 'charter/yachts':
            case 'request/arenda':
                $isForm['isHeader'] = $isForm['isContent'] = $isForm['isTitle'] = true;
                break;

            case 'charter':
                $isForm['isFlashMap'] = $isForm['isHeader'] = $isForm['isContent'] = $isForm['isTitle'] = true;
                break;
        }

        if($isForm['isContact']) {
			$form->add('phone',	    new cmfFormTextarea(array('max'=>10000, '!empty')));
//			$form->add('skype',	    new cmfFormText(array('max'=>255, '!empty')));
//			$form->add('icq',	    new cmfFormText(array('max'=>255, '!empty')));
			$form->add('adress',	new cmfFormTextarea(array('max'=>10000, '!empty')));
			$form->add('mainMenu',	new cmfFormTextarea(array('max'=>10000, '!empty')));
        }

        if($isForm['isContent']) {
            $form->add('header',    new cmfFormText(array('max'=>255, '!empty')));
            $form->add('content',    new cmfFormTextareaWysiwyng('main', $this->getId()));
        } elseif($isForm['isHeader']) {
            $form->add('header',    new cmfFormText(array('max'=>255, '!empty')));
        }
        if($isForm['isNotice']) {
            $form->add('notice',    new cmfFormTextareaWysiwyng('main', $this->getId()));
        }
        if($isForm['isFooter']) {
    		$form->add('footer',	new cmfFormTextareaWysiwyng('main', $this->getId()));
		}

        if($isForm['isFlashMap']) {
//            $form->add('flash',            new cmfFormFile(array('path'=>cmfPathMain)));
//            $form->add('flashXml',        new cmfFormTextarea());
//            $form->add('flashContent',    new cmfFormTextareaWysiwyng('arenda', $this->getId()));
        }

        if($isForm['isImage']) {
//            $form->add('image',        new cmfFormFile(array('path'=>cmfPathMain)));
        }

        if($isForm['isCoogleMap']) {
//            $form->add('mapGoogleApi',            new cmfFormTextarea(array('max'=>10000)));
//            $form->add('mapGoogleCoordinates',    new cmfFormText(array('max'=>255)));
//            $form->add('mapGoogleContent',        new cmfFormTextarea(array('max'=>255)));
        }

        if($isForm['isYandexMap']) {
//            $form->add('mapYandexApi',            new cmfFormTextarea(array('max'=>10000)));
//            $form->add('mapYandexCoordinates',    new cmfFormText(array('max'=>255)));
//            $form->add('mapYandexNotice',        new cmfFormTextarea(array('max'=>255)));
//            $form->add('mapYandexContent',        new cmfFormTextarea(array('max'=>10000)));
        }

        if($isForm['isTitle']) {
            $form->add('title',            new cmfFormTextarea(array('max'=>500)));
            $form->add('keywords',        new cmfFormTextarea(array('max'=>500)));
            $form->add('description',        new cmfFormTextarea(array('max'=>500)));
		}
		cmfGlobal::set('$isForm', $isForm);
	}

}

?>