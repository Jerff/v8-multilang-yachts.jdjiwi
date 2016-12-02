<?php

$info = cmfRegister::getSql()->placeholder("SELECT * FROM ?t WHERE id='contact'", db_main)
							->fetchAssoc();
if(!$info) return 404;
cmfMenu::setHeader($info['header']);
cmfMenu::add($info['header'], cmfGetUrl('/contact/'));
$this->assing('info', $info);

cmfSeo::set('title', $info['title']);
cmfSeo::set('keywords', $info['keywords']);
cmfSeo::set('description', $info['description']);


cmfGlobal::set('$headerId', 'contact');


cmfLoad('form/cmfContactForm');
$contact = new cmfContactForm();
$this->assing('contact', $contact);
$this->assing('form', $contact->getForm());


?>