<?php

class _config_site_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('_config_site_db');

        // �����
        $form = $this->getForm();
        $form->add('mainNewsView', new cmfFormSelectInt());
//        $form->add('mainArticleView', new cmfFormSelectInt());
        $form->add('mainYacthView', new cmfFormSelectInt());
        $form->add('rigthYacthView', new cmfFormSelectInt());
        $form->add('usdVsEuro', new cmfFormTextFloat());
        $form->add('usdVsRur', new cmfFormTextFloat());
        $form->add('usdVsUah', new cmfFormTextFloat());

        $form->add('sliderTime', new cmfFormSelectInt());

        $form->add('newsLimit', new cmfFormSelectInt());
        $form->add('newsPages', new cmfFormSelectInt());

        $form->add('articleLimit', new cmfFormSelectInt());
        $form->add('articlePages', new cmfFormSelectInt());

        $form->add('photoLimit', new cmfFormSelectInt());
        $form->add('photoPages', new cmfFormSelectInt());

        $form->add('boardLimit', new cmfFormSelectInt());
        $form->add('boardPages', new cmfFormSelectInt());

        $form->add('blogLimit', new cmfFormSelectInt());
        $form->add('blogPages', new cmfFormSelectInt());
        $form->add('blogFallover',	    new cmfFormTextarea(array('!empty')));
        $form->add('blogNetwork',	    new cmfFormTextarea(array('!empty')));

        $form->add('searchYachtsLimit', new cmfFormSelectInt());
        $form->add('searchYachtsPages', new cmfFormSelectInt());

        $form->add('wallpapersLimit', new cmfFormSelectInt());
        $form->add('wallpapersPages', new cmfFormSelectInt());

        $form->add('reviewsLimit', new cmfFormSelectInt());
        $form->add('reviewsPages', new cmfFormSelectInt());
    }

    public function loadForm() {
        $form = $this->getForm();
        for ($id = 1; $id <= 10; $id++) {
            $form->addElement('mainNewsView', $id, $id);
            $form->addElement('mainYacthView', $id, $id);
            $form->addElement('rigthYacthView', $id, $id);
//            $form->addElement('mainArticleView', $id, $id);
        }
        foreach (array(5, 10, 15, 20, 25, 30) as $id) {
            $form->addElement('newsLimit', $id, $id);
            $form->addElement('blogLimit', $id, $id);
            $form->addElement('articleLimit', $id, $id);
            $form->addElement('reviewsLimit', $id, $id);
        }

        for ($id = 3; $id <= 60; $id+=3) {
            $form->addElement('photoLimit', $id, $id);
            $form->addElement('wallpapersLimit', $id, $id);
            $form->addElement('searchYachtsLimit', $id, $id);
            $form->addElement('boardLimit', $id, $id);
        }

        for ($id = 5; $id <= 15; $id+=1) {
            $form->addElement('newsPages', $id, $id);
            $form->addElement('blogPages', $id, $id);
            $form->addElement('articlePages', $id, $id);
            $form->addElement('photoPages', $id, $id);
            $form->addElement('wallpapersPages', $id, $id);
            $form->addElement('reviewsPages', $id, $id);
            $form->addElement('boardPages', $id, $id);
            $form->addElement('searchYachtsPages', $id, $id);
        }

        for ($id = 1; $id <= 190; $id+=1) {
            $form->addElement('sliderTime', $id, $id);
        }
        /* 		for($id=12; $id<=60; $id+=3) {
          $form->addElement('shipyardsYachtsLimit', $id, $id);
          $form->addElement('brokerageYachtsLimit', $id, $id);
          }
          for($id=12; $id<=60; $id+=4) {
          $form->addElement('shipyardsYachtsFotoLimit', $id, $id);
          $form->addElement('brokerageYachtsFotoLimit', $id, $id);
          } */
    }

}

?>